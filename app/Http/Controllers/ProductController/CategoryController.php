<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryProduct as Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(10);

        if ($request->ajax()) {
            return view('Superadmin.Categories.partials.table', compact('categories'))->render();
        }

        return view('Superadmin.Categories.index', compact('categories'));
    }

public function create()
{
    $categories = Category::all();
    return view('superadmin.categories.create',compact('categories'));
}

public function store(Request $request)
{
    $request->validate([
        'name'=>'required',
        'img' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    Category::create([
        'name'=>$request->name,
        'img' => $request->file('img')->store('categories', 'public'),
        'slug'=>Str::slug($request->name).'-'.time(),
        'parent_id'=>$request->parent_id
    ]);

    return redirect()->route('superadmin.categories.index');
}

public function edit(Category $category)
{
    $categories = Category::where('id','!=',$category->id)->get();
    return view('superadmin.categories.edit',compact('category','categories'));
}

public function update(Request $request, Category $category)
{
$request->validate([
    'name' => 'required',
    'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
]);

$data = [
    'name' => $request->name,
    'slug' => Str::slug($request->name).'-'.time(),
    'parent_id' => $request->parent_id
];

if ($request->hasFile('img')) {

    // hapus gambar lama
    if ($category->img && Storage::disk('public')->exists($category->img)) {
        Storage::disk('public')->delete($category->img);
    }

    $data['img'] = $request->file('img')->store('categories', 'public');
}

$category->update($data);

return redirect()->route('superadmin.categories.index');
}

public function destroy(Category $category)
{
    $category->delete();
    return back();
}
}
