<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use App\Models\ConsentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ConsentDocumentController extends Controller
{
    public function index()
    {
        $documents = ConsentDocument::query()->ordered()->paginate(12);

        return view('Admin.consent-documents.index', compact('documents'));
    }

    public function create()
    {
        return view('Admin.consent-documents.form', [
            'document' => new ConsentDocument([
                'is_active' => true,
                'position' => ((int) ConsentDocument::max('position')) + 1,
            ]),
            'typeOptions' => ConsentDocument::typeOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        ConsentDocument::create($data);

        return redirect()
            ->route('admin.consent-documents.index')
            ->with('success', 'Consent document berhasil ditambahkan.');
    }

    public function edit(ConsentDocument $consentDocument)
    {
        return view('Admin.consent-documents.form', [
            'document' => $consentDocument,
            'typeOptions' => ConsentDocument::typeOptions(),
        ]);
    }

    public function update(Request $request, ConsentDocument $consentDocument)
    {
        $data = $this->validatedData($request, $consentDocument);

        $consentDocument->update($data);

        return redirect()
            ->route('admin.consent-documents.index')
            ->with('success', 'Consent document berhasil diperbarui.');
    }

    public function destroy(ConsentDocument $consentDocument)
    {
        $consentDocument->delete();

        return redirect()
            ->route('admin.consent-documents.index')
            ->with('success', 'Consent document berhasil dihapus.');
    }

    public function showPublic(string $slug)
    {
        $document = ConsentDocument::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('Users.legal.show', compact('document'));
    }

    private function validatedData(Request $request, ?ConsentDocument $document = null): array
    {
        $typeOptions = array_keys(ConsentDocument::typeOptions());

        $data = $request->validate([
            'type' => ['required', Rule::in($typeOptions)],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('consent_documents', 'slug')->ignore($document?->id),
            ],
            'summary' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'position' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        $slugExists = ConsentDocument::query()
            ->where('slug', $data['slug'])
            ->when($document, fn ($query) => $query->whereKeyNot($document->id))
            ->exists();

        if ($slugExists) {
            throw ValidationException::withMessages([
                'slug' => 'Slug sudah digunakan dokumen lain.',
            ]);
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['position'] = (int) ($data['position'] ?? ($document?->position ?? 0));

        return $data;
    }
}
