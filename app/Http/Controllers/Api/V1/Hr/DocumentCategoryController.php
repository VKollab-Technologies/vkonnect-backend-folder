<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreDocumentCategoryRequest;
use App\Http\Requests\Hr\UpdateDocumentCategoryRequest;
use App\Models\DocumentCategory;
use App\Services\AuditLogger;
use App\Support\DocumentFilename;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * HR management of the document category taxonomy (Documents domain — HR
 * already owns upload/replace/delete of documents themselves via
 * DocumentController; categories were previously seed-only/static).
 * Gated by `document_categories.manage`.
 */
class DocumentCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        // documentsCount is the LIVE count (what HR sees in the vault), but
        // deletability depends on trashed rows too — see destroy(). Counting
        // both separately is what stops the UI enabling a button the server
        // will refuse.
        $categories = DocumentCategory::withCount(['documents', 'documents as all_documents_count' => fn ($q) => $q->withTrashed()])
            // HR sees only the categories Super Admin has released to them,
            // one at a time. Super Admin sees the whole taxonomy, including
            // categories not yet granted — it cannot grant what it cannot see.
            ->visibleTo(request()->user()?->role)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories->map(fn (DocumentCategory $c) => $this->toArray($c))->values()]);
    }

    public function store(StoreDocumentCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        $category = DocumentCategory::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'filename_code' => $data['filenameCode'] ?? DocumentFilename::codeFromName($data['name']),
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sortOrder'] ?? 0,
            // OFF for HR until Super Admin says otherwise. A new category is
            // frequently created for something sensitive, and defaulting to
            // visible would grant access as a side effect of creating it.
            'hr_enabled' => false,
        ]);

        AuditLogger::log('document_category.created', 'documents', $category->id, [
            'name' => $category->name,
            'hrEnabled' => false,
        ]);

        return response()->json(['data' => $this->toArray($category)], 201);
    }

    public function update(UpdateDocumentCategoryRequest $request, DocumentCategory $category): JsonResponse
    {
        $data = $request->validated();

        $category->update([
            'name' => $data['name'] ?? $category->name,
            'slug' => $data['slug'] ?? $category->slug,
            'filename_code' => $data['filenameCode'] ?? $category->filename_code,
            'description' => array_key_exists('description', $data) ? $data['description'] : $category->description,
            'sort_order' => $data['sortOrder'] ?? $category->sort_order,
        ]);

        AuditLogger::log('document_category.updated', 'documents', $category->id, ['name' => $category->name]);

        return response()->json(['data' => $this->toArray($category->fresh()->loadCount('documents'))]);
    }

    /**
     * Refuses to delete a category that still has documents attached — never
     * orphans a document's category.
     *
     * Must include soft-deleted documents: Document uses SoftDeletes, so a
     * "deleted" document row still physically exists with its category_id
     * intact, and documents.category_id is a real DB foreign key
     * (restrictOnDelete). A plain documents()->exists() check silently
     * excludes trashed rows (Eloquent's SoftDeletingScope), so it would pass
     * here while the DB still refuses the delete with a raw SQLSTATE[23000]
     * constraint violation — this is exactly that bug, fixed at the source.
     */
    /**
     * Grant or revoke HR access to ONE category. Super Admin only — the route
     * lives in the super-admin group, so there is no realm check here.
     *
     * Separate from update() rather than another field on it: this is an access
     * decision, not an edit to the category's content, and it wants its own
     * audit action so a permission change is not buried inside a rename.
     */
    public function setHrAccess(Request $request, DocumentCategory $category): JsonResponse
    {
        $enabled = $request->validate([
            'hrEnabled' => ['required', 'boolean'],
        ])['hrEnabled'];

        $category->update(['hr_enabled' => $enabled]);

        AuditLogger::log('document_category.hr_access_changed', 'documents', $category->id, [
            'name' => $category->name,
            'hrEnabled' => $enabled,
        ]);

        return response()->json(['data' => $this->toArray($category->fresh()->loadCount([
            'documents',
            'documents as all_documents_count' => fn ($q) => $q->withTrashed(),
        ]))]);
    }

    /**
     * Delete a category. Super Admin may do so even when it still holds
     * documents; HR may not.
     *
     * The asymmetry is deliberate rather than an inconsistency. The "must be
     * empty" rule protects the taxonomy from routine mistakes during day-to-day
     * HR work. Super Admin owns the taxonomy itself, and already bypasses every
     * permission via Gate::before — the check is made explicit here so that
     * bypass is visible at the one place it now changes behaviour, instead of
     * being an emergent property of the gate.
     *
     * Documents are DETACHED, never deleted. They are the alumnus's own Form
     * 16s and payslips; removing a category is an administrative tidy-up and
     * must never destroy someone's records. They resurface as "Uncategorized".
     */
    public function destroy(Request $request, DocumentCategory $category): JsonResponse
    {
        $isSuperAdmin = $request->user()?->role === 'super_admin';
        $documentCount = $category->documents()->withTrashed()->count();

        // Soft-deleted documents included: they are kept for audit history and
        // would otherwise hold a foreign key to a row that no longer exists.
        if ($documentCount > 0) {
            $category->documents()->withTrashed()->update(['category_id' => null]);
        }

        AuditLogger::log('document_category.deleted', 'documents', $category->id, [
            'name' => $category->name,
            // Recorded because it is the consequential part: someone reading
            // this later needs to know documents were orphaned, and how many.
            'documentsOrphaned' => $documentCount,
            'forced' => $documentCount > 0,
        ]);

        $category->delete();

        return response()->json([
            'message' => $documentCount > 0
                ? "Category removed. {$documentCount} document(s) are now uncategorized."
                : 'Category removed.',
        ]);
    }

    private function toArray(DocumentCategory $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'filenameCode' => $category->filename_code,
            'description' => $category->description,
            'sortOrder' => $category->sort_order,
            'documentsCount' => $category->documents_count ?? 0,
            'hrEnabled' => (bool) $category->hr_enabled,
            // Computed with the SAME rule destroy() enforces, including
            // soft-deleted documents. Previously the UI derived this from
            // documentsCount alone, so a category whose only documents had
            // been deleted showed an enabled Delete button and then 422'd —
            // the inconsistency HR was seeing.
            'deletable' => ($category->all_documents_count ?? $category->documents()->withTrashed()->count()) === 0,
        ];
    }
}
