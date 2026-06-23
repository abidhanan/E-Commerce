<?php

namespace App\Http\Controllers\LandingPageController;

use App\Http\Controllers\Controller;
use App\Models\Display;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DisplayController extends Controller
{
    public function index()
    {
        // Menggunakan paginate agar dasbor tidak meledak jika admin iseng membuat ratusan display
        $displays = Display::latest()->paginate(10);
        return view('Admin.displays.index', compact('displays'));
    }

    public function create()
    {
        return view('Admin.displays.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // HANDLE UPLOAD IMAGE & SAKELAR AKTIF
        for ($i = 1; $i <= 3; $i++) {
            $field = "image_{$i}_path";
            $activeField = "image_{$i}_is_active";

            // MENANGKAP STATUS CHECKBOX DARI FORMULIR ADMIN
            $data[$activeField] = $request->has($activeField);

            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('displays', 'public');
            }
        }

        Display::create($data);

        return redirect()->route('admin.displays.index')
            ->with('success', 'Display berhasil dibuat. Banner terbaru otomatis tayang di halaman utama.');
    }

    public function edit(Display $display)
    {
        return view('Admin.displays.edit', compact('display'));
    }

    public function update(Request $request, Display $display)
    {
        $data = $this->validateData($request);

        for ($i = 1; $i <= 3; $i++) {
            $field = "image_{$i}_path";
            $activeField = "image_{$i}_is_active";

            // MENANGKAP STATUS CHECKBOX DARI FORMULIR ADMIN
            $data[$activeField] = $request->has($activeField);

            if ($request->hasFile($field)) {
                // DELETE OLD FILE (Pertahanan ketat agar tidak error jika path kosong)
                if (!empty($display->$field) && Storage::disk('public')->exists($display->$field)) {
                    Storage::disk('public')->delete($display->$field);
                }

                // STORE NEW FILE
                $data[$field] = $request->file($field)->store('displays', 'public');
            } else {
                // KEEP OLD FILE (Menghapus dari array $data agar update() tidak menimpanya menjadi null)
                unset($data[$field]);
            }
        }

        $display->update($data);

        return redirect()->route('admin.displays.index')
            ->with('success', 'Display berhasil diperbarui.');
    }

    public function destroy(Display $display)
    {
        // DELETE ALL IMAGES FROM STORAGE SEBELUM MENGHAPUS ROW
        for ($i = 1; $i <= 3; $i++) {
            $field = "image_{$i}_path";

            if (!empty($display->$field) && Storage::disk('public')->exists($display->$field)) {
                Storage::disk('public')->delete($display->$field);
            }
        }

        $display->delete();

        return redirect()->route('admin.displays.index')
            ->with('success', 'Display berhasil dihapus dari sistem.');
    }

    /**
     * VALIDATION LOGIC MUTLAK
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'image_1_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'image_2_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'image_3_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',

            'image_1_title' => 'nullable|string|max:255',
            'image_2_title' => 'nullable|string|max:255',
            'image_3_title' => 'nullable|string|max:255',

            'image_1_sub_title' => 'nullable|string|max:255',
            'image_2_sub_title' => 'nullable|string|max:255',
            'image_3_sub_title' => 'nullable|string|max:255',

            'image_1_link' => 'nullable|url|max:255',
            'image_2_link' => 'nullable|url|max:255',
            'image_3_link' => 'nullable|url|max:255',

            // Validasi keberadaan input checkbox dari panel admin
            'image_1_is_active' => 'nullable',
            'image_2_is_active' => 'nullable',
            'image_3_is_active' => 'nullable',

            'running_text' => 'nullable|string',
        ]);
    }
}