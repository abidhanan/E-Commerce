<?php

namespace App\Http\Controllers\BlogController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CategoryBlog as Category;
use App\Models\Post;
use App\Models\TagBlog;
use App\Support\HtmlSanitizer;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class BlogController extends Controller
{
    public function index(Request $request)
{
   
    $query = Post::with('category');

    if ($request->search) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    $posts = $query->latest()->paginate(10);

    if($request->ajax()) {
        return view('Admin.Blogs.partials.table', compact('posts'))->render();
    }

    return view('Admin.Blogs.index', compact('posts'));
}

    public function create()
    {
        $categories = Category::all();
        $tags = TagBlog::all();
        return view('Admin.Blogs.create', compact('categories','tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required',
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tag_blogs,id'
        ],
        [
            'category_id.required' => 'Kategori harus dipilih.',
            'title.required' => 'Judul tidak boleh kosong.',
            'content.required' => 'Konten tidak boleh kosong.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'tags.array' => 'Tags harus berupa array.',
            'tags.*.exists' => 'Tag yang dipilih tidak valid.',
           
        ]);

        if (Post::where('title', trim($validated['title']))->exists()) {
            return back()
                ->withErrors([
                    'title' => 'Judul blog sudah digunakan'
                ])
                ->withInput();
        }
        $cleanContent = $this->cleanTrix($validated['content']);

       
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $imagePath = $file->storeAs('blogs', $filename, 'public');
        }

    $post = Post::create([
        'user_id'     => auth()->id(),
        'category_id' => $validated['category_id'],
        'tag_id'      => $validated['tags'] ?? [],
        'title'       => trim($validated['title']),
        'slug'        => Str::slug($validated['title']),
        'content'     => $cleanContent,
        'thumbnail'   => $imagePath,
        'status'      => 'draft',
    ]);
    return redirect()
        ->route('admin.blogs.index')
        ->with('success', 'Blog berhasil dibuat');
}

    private function cleanTrix($content)
    {
        return HtmlSanitizer::clean(
            (string) $content,
            ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'blockquote', 'div', 'h1', 'h2', 'h3', 'a', 'img'],
            true
        );
    }
public function relaseblog(Post $blog)
{
    $blog->update(['status' => 'published']);

    return redirect()
        ->route('admin.blogs.index')
        ->with('success', 'Blog berhasil dirilis');
}

public function edit(Post $blog)
{
    $categories = Category::all();
    $tags = TagBlog::all();
    return view('Admin.blogs.edit', compact('blog', 'categories','tags'));
}

public function update(Request $request, Post $blog)
{
    $validated = $request->validate([
        'category_id' => 'required',
        'title' => [
            'required',
            'string',
            'max:255',
            Rule::unique('posts', 'title')->ignore($blog->id)
        ],
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tag_blogs,id'
    ], [
        'title.unique' => 'Judul blog sudah digunakan'
    ]);

    // 🔥 FILTER TRIX CONTENT
    $cleanContent = $this->cleanTrix($validated['content']);
   
    // upload image
    if ($request->hasFile('image')) {
        // hapus thumbnail lama
        if ($blog->thumbnail) {
            \Storage::disk('public')->delete($blog->thumbnail);
        }

        $file = $request->file('image');
        $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
        $imagePath = $file->storeAs('blogs', $filename, 'public');
        $blog->thumbnail = $imagePath;
    }

    $blog->update([
        'category_id' => $validated['category_id'],
        'title' => $validated['title'],
        'content' => $cleanContent,
        'tag_id' => $validated['tags'] ?? []
    ]);

    return redirect()
        ->route('admin.blogs.index')
        ->with('success', 'Blog berhasil diperbarui');
}


public function destroy(Post $blog)
{
    

    // hapus thumbnail
    if ($blog->thumbnail) {
        \Storage::disk('public')->delete($blog->thumbnail);
    }

    $blog->delete();

    return redirect()
        ->route('admin.blogs.index')
        ->with('success', 'Blog berhasil dihapus');
}

}
