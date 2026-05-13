<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::query()
            ->ordered()
            ->get();

        return view('Admin.social-links.index', [
            'socialLinks' => $socialLinks,
            'typeOptions' => SocialLink::typeOptions(),
        ]);
    }

    public function create()
    {
        return view('Admin.social-links.form', $this->formViewData(new SocialLink()));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        SocialLink::create($data);

        return redirect()
            ->route('admin.social-links.index')
            ->with('success', 'Link sosial berhasil ditambahkan.');
    }

    public function edit(SocialLink $socialLink)
    {
        return view('Admin.social-links.form', $this->formViewData($socialLink));
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $data = $this->validateData($request, $socialLink);

        $socialLink->update($data);

        return redirect()
            ->route('admin.social-links.index')
            ->with('success', 'Link sosial berhasil diperbarui.');
    }

    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();

        return redirect()
            ->route('admin.social-links.index')
            ->with('success', 'Link sosial berhasil dihapus.');
    }

    private function formViewData(SocialLink $socialLink): array
    {
        return [
            'socialLink' => $socialLink,
            'typeOptions' => SocialLink::typeOptions(),
            'platformOptions' => SocialLink::platformOptions(),
        ];
    }

    private function validateData(Request $request, ?SocialLink $socialLink = null): array
    {
        $typeOptions = array_keys(SocialLink::typeOptions());
        $flatPlatforms = array_keys(SocialLink::flattenedPlatformOptions());
        $url = $this->normalizeUrl((string) $request->input('url', ''));

        $validator = Validator::make(
            array_merge($request->all(), ['url' => $url]),
            [
                'type' => ['required', Rule::in($typeOptions)],
                'platform' => ['required', Rule::in($flatPlatforms)],
                'label' => ['nullable', 'string', 'max:255'],
                'url' => ['required', 'url', 'max:2048'],
                'position' => ['nullable', 'integer', 'min:0', 'max:9999'],
                'is_active' => ['nullable', 'boolean'],
            ],
            [
                'url.url' => 'URL harus valid. Contoh: https://instagram.com/namatoko',
            ]
        );

        $validator->after(function ($validator) use ($request) {
            $type = (string) $request->input('type');
            $platform = (string) $request->input('platform');
            $label = trim((string) $request->input('label'));
            $validPlatforms = array_keys(SocialLink::platformOptions($type));

            if ($type && $platform && !in_array($platform, $validPlatforms, true)) {
                $validator->errors()->add('platform', 'Platform tidak sesuai dengan kategori yang dipilih.');
            }

            if ($platform === 'custom' && $label === '') {
                $validator->errors()->add('label', 'Nama tampil wajib diisi untuk platform custom.');
            }
        });

        $data = $validator->validate();

        $data['label'] = trim((string) ($data['label'] ?? ''));
        $data['label'] = $data['label'] !== '' ? $data['label'] : SocialLink::labelForPlatform($data['platform']);
        $data['url'] = $url;
        $data['position'] = array_key_exists('position', $data) && $data['position'] !== null
            ? (int) $data['position']
            : (($socialLink?->position) ?? $this->nextPosition($data['type']));
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return $url;
        }

        if (!preg_match('~^[a-z][a-z0-9+.-]*://~i', $url)) {
            return 'https://' . ltrim($url, '/');
        }

        return $url;
    }

    private function nextPosition(string $type): int
    {
        return ((int) SocialLink::query()->where('type', $type)->max('position')) + 1;
    }
}
