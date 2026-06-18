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
                return view('admin.categories.partials.table', compact('categories'))->render();
            }

            return view('admin.categories.index', compact('categories'));
        }

    public function create()
    {
        $categories = Category::all();
        // Hitung slot yang terpakai
        $featuredCount = Category::where('is_featured_home', true)->count();
        
        return view('Admin.Categories.create', compact('categories', 'featuredCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'img'  => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // PERTAHANAN MUTLAK: Hitung slot yang tersedia sebelum menyimpan
        if ($request->has('is_featured_home')) {
            $featuredCount = Category::where('is_featured_home', true)->count();
            if ($featuredCount >= 3) {
                return back()
                    ->withErrors(['is_featured_home' => 'Slot Beranda Penuh! Maksimal hanya 3 kategori yang boleh ditampilkan. Copot centang pada kategori lain terlebih dahulu di tabel.'])
                    ->withInput(); // Mengembalikan input agar admin tidak perlu mengetik ulang nama & parent
            }
        }

        Category::create([
            'name' => $request->name,
            'img'  => $request->file('img')->store('categories', 'public'),
            'slug' => Str::slug($request->name) . '-' . time(),
            'parent_id' => $request->parent_id,
            'is_featured_home' => $request->has('is_featured_home') 
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        // Hitung slot yang terpakai
        $featuredCount = Category::where('is_featured_home', true)->count();
        
        return view('Admin.Categories.edit', compact('category', 'categories', 'featuredCount'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'img'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // PERTAHANAN UPDATE CERDAS
        // Hanya cegat JIKA kategori ini sebelumnya TIDAK TAYANG, lalu admin ingin MENAYANGKANNYA
        if ($request->has('is_featured_home') && !$category->is_featured_home) {
            $featuredCount = Category::where('is_featured_home', true)->count();
            if ($featuredCount >= 3) {
                return back()
                    ->withErrors(['is_featured_home' => 'Slot Beranda Penuh! Maksimal hanya 3 kategori yang boleh ditampilkan. Copot centang pada kategori lain terlebih dahulu.'])
                    ->withInput();
            }
        }

        $data = [
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'is_featured_home' => $request->has('is_featured_home') 
        ];

        if ($request->hasFile('img')) {
            if ($category->img && Storage::disk('public')->exists($category->img)) {
                Storage::disk('public')->delete($category->img);
            }
            $data['img'] = $request->file('img')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back();
    }
}