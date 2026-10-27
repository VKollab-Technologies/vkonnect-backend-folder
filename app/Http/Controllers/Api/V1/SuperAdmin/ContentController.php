<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Super Admin editor for public content blocks (Terms, Privacy, footer blurb).
 */
class ContentController extends Controller
{
    public function __construct(private readonly NotificationService $notifications) {}
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => ContentBlock::orderBy('key')->get()->map(fn (ContentBlock $b) => [
                'key' => $b->key,
                'title' => $b->title,
                'body' => $b->body,
                'updatedAt' => $b->updated_at?->toIso8601String(),
            ])->values(),
        ]);
    }

    public function update(Request $request, string $key): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:50000'],
        ]);

        $block = ContentBlock::where('key', $key)->firstOrFail();
        $block->update([
            'title' => $data['title'],
            'body' => $data['body'],
            'updated_by' => $request->user()->id,
        ]);

        AuditLogger::log('content.updated', 'content', $block->id, ['key' => $key]);

        $this->notifications->notifyRoles(
            ['hr_admin'],
            'system',
            'Platform Update',
            "{$request->user()->name} updated \"{$block->title}\" content.",
            '/hr/settings',
        );

        return response()->json([
            'key' => $block->key,
            'title' => $block->title,
            'body' => $block->body,
            'updatedAt' => $block->updated_at?->toIso8601String(),
        ]);
    }
}
