<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SizeGuide;

class SizeGuideController extends Controller
{
    public function index(Request $request)
    {
        $query = SizeGuide::query();

        if ($request->search) {
            $query->where('type', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%");
        }

        $sizeGuides = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('Admin.SizeGuides.partials.table', compact('sizeGuides'))->render(),
                'pagination' => $sizeGuides->links()->render()
            ]);
        }

        return view('Admin.SizeGuides.index', compact('sizeGuides'));
    }

    public function create()
    {
        return view('Admin.SizeGuides.create');
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|string|max:100',
        'name' => 'nullable|string|max:255',
        'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'data' => 'required|array',
    ]);

    $data = [
        'sizes' => array_values($validated['data'])
    ];

    $this->validateMeasurements($data);

    $imgPath = null;

    if ($request->hasFile('img')) {
        $imgPath = $request->file('img')->store('size_guides', 'public');
    }

    SizeGuide::create([
        'type' => $validated['type'],
        'name' => $validated['name'],
        'img' => $imgPath,
        'data' => $data
    ]);

    return redirect()
        ->route('admin.size-guides.index')
        ->with('success', 'Size guide created successfully.');
}

   public function edit(SizeGuide $sizeGuide)

    {
        // return $sizeGuide;
        return view('Admin.SizeGuides.edit', compact('sizeGuide'));
    }

   public function update(Request $request, string $id)
    {
        $sizeGuide = SizeGuide::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|max:100',
            'name' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'data' => 'required|array',
        ]);

        $data = [
            'sizes' => array_values($validated['data'])
        ];

        $this->validateMeasurements($data);

       
        $imgPath = $sizeGuide->img;
        
        
        if ($request->hasFile('img')) {
            
           
            if ($sizeGuide->img && \Storage::disk('public')->exists($sizeGuide->img)) {
                \Storage::disk('public')->delete($sizeGuide->img);
            }

            
            $imgPath = $request->file('img')->store('size_guides', 'public');
        }

        $sizeGuide->update([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'img' => $imgPath,
            'data' => $data
        ]);

        return redirect()
            ->route('admin.size-guides.index')
            ->with('success', 'Size guide updated successfully.');
    }

    public function destroy(string $id)
    {
        $sizeGuide = SizeGuide::findOrFail($id);
        $sizeGuide->delete();

        return redirect()
            ->route('admin.size-guides.index')
            ->with('success', 'Size guide deleted successfully.');
    }

    private function validateMeasurements(array $data)
{
    if (!isset($data['sizes'])) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'data' => 'Format salah: sizes wajib ada'
        ]);
    }

    foreach ($data['sizes'] as $sizeIndex => $size) {

        //  size wajib ada
        if (empty($size['size'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'data' => "Size pada index ke-" . ($sizeIndex + 1) . " wajib diisi"
            ]);
        }

        //  measurements wajib ada
        if (!isset($size['measurements']) || !is_array($size['measurements']) || !count($size['measurements'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'data' => "Size {$size['size']} harus punya minimal 1 variant"
            ]);
        }

        foreach ($size['measurements'] as $mIndex => $m) {

            // label wajib
            if (empty($m['label'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'data' => "Field pada size {$size['size']} wajib diisi"
                ]);
            }

            // type wajib
            if (!isset($m['type'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'data' => "Type pada {$m['label']} wajib ada"
                ]);
            }

            // range
            if ($m['type'] === 'range') {
                if ($m['min'] === null || $m['max'] === null) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'data' => "{$m['label']} harus punya min & max"
                    ]);
                }
            }

         
            if ($m['type'] === 'simple') {
                if ($m['value'] === null || $m['value'] === '') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'data' => "{$m['label']} harus punya value"
                    ]);
                }
            }
        }
    }
}
}