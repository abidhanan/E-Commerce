<?php

namespace App\Http\Controllers\LandingPageController;

use App\Http\Controllers\Controller;
use App\Models\Display;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
class DisplayController extends Controller
{
    public function index()
    {
        $displays = Display::latest()->get();
        return view('Admin.displays.index', compact('displays'));
    }

    public function create()
    {
        $products = Product::all();
        return view('Admin.displays.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // HANDLE UPLOAD IMAGE
        for ($i = 1; $i <= 3; $i++) {
            $field = "image_{$i}_path";

            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('displays', 'public');
            }
        }

        Display::create($data);

        return redirect()->route('admin.displays.index')
            ->with('success', 'Display berhasil dibuat');
    }

    public function edit(Display $display)
    {
        $products = Product::all();
        return view('Admin.displays.create', compact('display', 'products'));
    }
    
    

    public function update(Request $request, Display $display)
    {
        $data = $this->validateData($request);

        for ($i = 1; $i <= 3; $i++) {
            $field = "image_{$i}_path";

            if ($request->hasFile($field)) {

                // DELETE OLD FILE
                if ($display->$field && Storage::disk('public')->exists($display->$field)) {
                    Storage::disk('public')->delete($display->$field);
                }

                // STORE NEW FILE
                $data[$field] = $request->file($field)->store('displays', 'public');
            } else {
                // KEEP OLD FILE
                unset($data[$field]);
            }
        }

        $display->update($data);

        return redirect()->route('admin.displays.index')
            ->with('success', 'Display berhasil diupdate');
    }

    public function destroy(Display $display)
    {
        // DELETE ALL IMAGES
        for ($i = 1; $i <= 3; $i++) {
            $field = "image_{$i}_path";

            if ($display->$field && Storage::disk('public')->exists($display->$field)) {
                Storage::disk('public')->delete($display->$field);
            }
        }

        $display->delete();

        return redirect()->route('admin.displays.index')
            ->with('success', 'Display berhasil dihapus');
    }

    /**
     * VALIDATION LOGIC
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            // IMAGE
            'image_1_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image_2_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image_3_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // TITLE
            'image_1_title' => 'nullable|string|max:255',
            'image_2_title' => 'nullable|string|max:255',
            'image_3_title' => 'nullable|string|max:255',

            // SUB TITLE (ONLY IMAGE 1)
            'image_1_sub_title' => 'nullable|string|max:255',
            'image_2_sub_title' => 'nullable|string|max:255',
            'image_3_sub_title' => 'nullable|string|max:255',

            // RUNNING TEXT
            'running_text' => 'nullable|string',
        ]);
    }
}