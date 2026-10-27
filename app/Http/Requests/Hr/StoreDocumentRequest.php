<?php

namespace App\Http\Requests\Hr;

use App\Models\AlumniMaster;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Categories that are inherently period-based (a salary run, a tax year)
     * require a Financial Year; one-time documents (PAN, Aadhaar,
     * Full & Final Settlement, Experience Letter) don't. Keyed by the fixed
     * category slugs — an unrecognized/custom category defaults to requiring
     * it, since that's the safer default (asking for more beats silently
     * accepting less).
     */
    private const PERIOD_BASED_SLUGS = [
        'salary-slips',
        'form-16',
        // Both spellings on purpose. The migrations create 'increment-letter';
        // the live database has 'salary-increment-letter', because that
        // category was added through the Super Admin UI rather than seeded, and
        // the UI derived the slug from whatever name was typed.
        //
        // Worth knowing this list is keyed on slugs while categories are
        // user-creatable: a category someone adds tomorrow cannot be
        // period-based without a code change. The durable fix is a column on
        // document_categories (none | annual | monthly) rather than a constant
        // here — deliberately not done in this change, which is already
        // touching upload, storage, naming, filtering and display.
        'increment-letter',
        'salary-increment-letter',
    ];

    /**
     * Categories filed per MONTH rather than per financial year.
     *
     * Only Salary Slips. Everything else in PERIOD_BASED_SLUGS is annual, so
     * a month on those would be meaningless and is rejected rather than
     * silently dropped — a value the API accepts and discards is worse than
     * one it refuses, because the caller believes it was stored.
     */
    private const MONTHLY_SLUGS = ['salary-slips'];

    /**
     * Categories that hold many different documents rather than one kind, so
     * the category code alone cannot name the file. Without a per-document
     * name every upload here becomes EMPID_PERSONAL_DOCS and they are
     * indistinguishable on disk and in the vault — which is exactly what
     * happened while this was optional.
     */
    private const CATCH_ALL_SLUGS = ['personal-documents'];

    public function authorize(): bool
    {
        return true; // gated by route permission middleware
    }

    public function rules(): array
    {
        $category = DocumentCategory::find($this->input('categoryId'));
        $requiresYear = $category === null || in_array($category->slug, self::PERIOD_BASED_SLUGS, true);
        $requiresDocumentName = $category !== null && in_array($category->slug, self::CATCH_ALL_SLUGS, true);
        $isMonthly = $category !== null && in_array($category->slug, self::MONTHLY_SLUGS, true);

        return [
            // Either an activated alumnus or an unclaimed roster record — HR
            // can file documents before the person registers.
            //
            // Can't use a plain exists rule: the id may live in either of two
            // tables. This asserts that it is an alumnus OR an unclaimed
            // roster row, and never an HR/Super Admin account. The controller
            // re-applies the same scoping when it resolves the recipient, so
            // neither layer alone is load-bearing.
            'userId' => [
                'required',
                'uuid',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $isAlumnus = User::where('role', 'alumni')->whereKey($value)->exists();
                    $isRoster = AlumniMaster::whereNull('claimed_by')->whereKey($value)->exists();

                    if (! $isAlumnus && ! $isRoster) {
                        $fail('Select a valid alumnus.');
                    }
                },
            ],
            // Existence is not enough: HR may only file into a category Super
            // Admin has granted them. Enforced here rather than only by
            // filtering the dropdown, since the id arrives from the client.
            'categoryId' => [
                'required',
                'uuid',
                Rule::exists('document_categories', 'id')->where(
                    fn ($q) => $this->user()?->role === 'hr_admin' ? $q->where('hr_enabled', true) : $q,
                ),
            ],
            'title' => ['required', 'string', 'max:160'],
            // Optional DOCNAME override for catch-all categories (Personal
            // Documents), so PAN / Aadhaar / etc. filed under one category are
            // still individually identifiable in the generated filename.
            'documentName' => [$requiresDocumentName ? 'required' : 'nullable', 'string', 'max:60'],
            'financialYear' => [$requiresYear ? 'required' : 'nullable', 'string', 'regex:/^\d{4}-\d{4}$/'],
            // Required alongside the financial year for monthly categories,
            // and refused entirely for the rest.
            'periodMonth' => $isMonthly
                ? ['required_with:financialYear', 'integer', 'between:1,12']
                : ['prohibited'],
            'file' => ['required', 'file', new \App\Rules\PdfFile(10240)], // documents are PDF only
        ];
    }

    public function messages(): array
    {
        return [
            'documentName.required' => 'Choose which document this is, so the filename identifies it.',
            'financialYear.required' => 'Financial year is required for this document category.',
            'financialYear.regex' => 'Financial year must look like 2024-2025.',
            'periodMonth.required_with' => 'Choose the month this payslip covers.',
            'periodMonth.between' => 'Month must be between 1 and 12.',
            'periodMonth.prohibited' => 'Only Salary Slips are filed by month.',
            'file.max' => 'The file may not be larger than 10 MB.',
        ];
    }
}
