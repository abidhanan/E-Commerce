<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collections;
use App\Models\Product;
use Illuminate\Support\Str;

class CollectionsController extends Controller
{
   public function index(Request $request)
{
    $query = Collections::withCount('products');

    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $collections = $query->latest()->paginate(10);

    if ($request->ajax()) {
        return view('superadmin.collections.partials.table', compact('collections'))->render();
    }

    return view('SuperAdmin.Collections.index', compact('collections'));
}

    public function create()
    {
        return view('SuperAdmin.Collections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'img' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        Collections::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name).'-'.time(),
            'img' => $request->file('img')->store('collections','public')
        ]);

        return redirect()->route('superadmin.collections.index');
    }

    public function edit(Collections $collection)
    {
        $collections = Collections::with('products')->find($collection->id);
        return view('SuperAdmin.Collections.edit', compact('collections'));
    }

    public function update(Request $request, Collections $collection)
    {
        $request->validate([
            'name' => 'required',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name).'-'.time(),
        ];

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('collections','public');
        }

        $collection->update($data);

        return redirect()->route('superadmin.collections.index');
    }

    public function destroy(Collections $collection)
    {
        $collection->delete();

        return redirect()->route('superadmin.collections.index');
    }
}