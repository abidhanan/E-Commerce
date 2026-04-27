<?php

namespace App\Http\Controllers\BlogController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryBlog as Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest()->get();

        return view('SuperAdmin.blog.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('SuperAdmin.blog.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_blogs,name',
        ]);

        $name = trim($request->name);

        Category::create([
            'name' => $name,
            'slug' => $this->generateUniqueSlug($name),
        ]);

        return redirect()->route('superadmin.blog-categories.index')
            ->with('success', 'Kategori blog berhasil dibuat.');
    }

    public function edit(Category $blog_category)
    {
        return view('SuperAdmin.blog.categories.edit', compact('blog_category'));
    }

    public function update(Request $request, Category $blog_category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_blogs,name,' . $blog_category->id,
        ]);

        $name = trim($request->name);

        $blog_category->update([
            'name' => $name,
            'slug' => $this->generateUniqueSlug($name, $blog_category),
        ]);

        return redirect()->route('superadmin.blog-categories.index')
            ->with('success', 'Kategori blog berhasil diperbarui.');
    }

    public function destroy(Category $blog_category)
    {
        $blog_category->delete();

        return redirect()->route('superadmin.blog-categories.index')
            ->with('success', 'Kategori blog berhasil dihapus.');
    }

    private function generateUniqueSlug(string $name, ?Category $ignoreCategory = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'kategori-blog';
        }

        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $ignoreCategory)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?Category $ignoreCategory = null): bool
    {
        $query = Category::where('slug', $slug);

        if ($ignoreCategory) {
            $query->where('id', '!=', $ignoreCategory->id);
        }

        return $query->exists();
    }
}
