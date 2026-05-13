<?php

namespace App\Http\Controllers\BlogController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryBlog as Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
   public function index(Request $request)
    {
        $categories = Category::latest()->get();

        return view('Admin.Blogs.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('Admin.Blogs.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        if (Category::where('name', trim($request->name))->exists()) {
            return back()
                ->withErrors([
                    'name' => 'Nama kategori sudah digunakan'
                ])
                ->withInput();
        }
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.blog-categories.index');
    }

    public function edit(Category $blog_category)
    {
        return view('Admin.Blogs.categories.edit', compact('blog_category'));
    }

   public function update(Request $request, Category $blog_category)
{
    $slug = Str::slug($request->name);

    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('category_blogs', 'name')->ignore($blog_category->id),
        ],
    ], [
        'name.required' => 'Nama kategori tidak boleh kosong.',
        'name.unique' => 'Nama kategori sudah digunakan.',
    ]);

    // cek slug duplicate
    if (
        Category::where('slug', $slug)
            ->where('id', '!=', $blog_category->id)
            ->exists()
    ) {
        return back()
            ->withErrors([
                'name' => 'Nama kategori sudah digunakan oleh kategori lain.'
            ])
            ->withInput();
    }

    $blog_category->update([
        'name' => trim($request->name),
        'slug' => $slug,
    ]);

    return redirect()
        ->route('admin.blog-categories.index')
        ->with('success', 'Category updated');
}

    public function destroy(Category $blog_category)
    {
        $blog_category->delete();
        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category deleted');
    }
}