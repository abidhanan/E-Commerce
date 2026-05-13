<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
       
        $query = Material::query();

        if ($request->search) {
            $query->where('material', 'like', "%{$request->search}%");
        }

        $materials = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('Admin.Materials.partials.table', compact('materials'))->render(),
                'pagination' => $materials->links()->render()
            ]);
        }

        return view('Admin.Materials.index', compact('materials'));
    }

    public function create()
    {
        return view('Admin.Materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // upload image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('materials', 'public');
        }

        Material::create($validated);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Material created successfully.');
    }

    public function edit(string $id)
    {
        $material = Material::findOrFail($id);
        return view('Admin.Materials.edit', compact('material'));
    }

    public function update(Request $request, string $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'material' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // kalau upload baru → replace
        if ($request->hasFile('image')) {
            if ($material->image) {
                Storage::disk('public')->delete($material->image);
            }

            $validated['image'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($validated);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Material updated successfully.');
    }

    public function destroy(string $id)
    {
        $material = Material::findOrFail($id);

        if ($material->image) {
            Storage::disk('public')->delete($material->image);
        }

        $material->delete();

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Material deleted successfully.');
    }
}