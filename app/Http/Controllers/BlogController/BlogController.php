<?php

namespace App\Http\Controllers\BlogController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryBlog as Category;
use App\Models\Post;
use App\Models\TagBlog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
       
        $query = Post::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('SuperAdmin.blog.partials.table', compact('posts'))->render();
        }

        return view('SuperAdmin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = TagBlog::orderBy('name')->get();

        return view('SuperAdmin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:category_blogs,id',
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tag_blogs,id'
        ]);

        $cleanContent = $this->cleanTrix($validated['content']);
        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('blogs', $filename, 'public');
        }

        Post::create([
            'user_id'      => auth()->id(),
            'category_id'  => $validated['category_id'],
            'tag_id'       => $validated['tags'] ?? [],
            'title'        => trim($validated['title']),
            'slug'         => $this->generateUniqueSlug($validated['title']),
            'excerpt'      => Str::limit(trim(strip_tags($cleanContent)), 160),
            'content'      => $cleanContent,
            'thumbnail'    => $imagePath,
            'status'       => 'draft',
            'published_at' => null,
        ]);

        return redirect()
            ->route('superadmin.blogs.index')
            ->with('success', 'Blog berhasil dibuat');
    }

    public function relaseblog(Post $blog)
    {
        $blog->update([
            'status' => 'published',
            'published_at' => $blog->published_at ?? now(),
        ]);

        return redirect()
            ->route('superadmin.blogs.index')
            ->with('success', 'Blog berhasil dipublish');
    }

    public function edit(Post $blog)
    {
        $categories = Category::orderBy('name')->get();
        $tags = TagBlog::orderBy('name')->get();

        return view('SuperAdmin.blog.edit', compact('blog', 'categories', 'tags'));
    }

    public function update(Request $request, Post $blog)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:category_blogs,id',
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tag_blogs,id'
        ]);

        $cleanContent = $this->cleanTrix($validated['content']);
        $data = [
            'category_id' => $validated['category_id'],
            'title'       => trim($validated['title']),
            'slug'        => $blog->title !== trim($validated['title'])
                ? $this->generateUniqueSlug($validated['title'], $blog)
                : $blog->slug,
            'excerpt'     => Str::limit(trim(strip_tags($cleanContent)), 160),
            'content'     => $cleanContent,
            'tag_id'      => $validated['tags'] ?? [],
        ];

        if ($request->hasFile('image')) {
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $data['thumbnail'] = $file->storeAs('blogs', $filename, 'public');
        }

        if ($blog->status === 'published' && ! $blog->published_at) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return redirect()
            ->route('superadmin.blogs.index')
            ->with('success', 'Blog berhasil diperbarui');
    }

    public function destroy(Post $blog)
    {
        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();

        return redirect()
            ->route('superadmin.blogs.index')
            ->with('success', 'Blog berhasil dihapus');
    }

    private function cleanTrix(string $content): string
    {
        $content = preg_replace('#<(script|iframe|object|embed)(.*?)>(.*?)</\1>#is', '', $content);
        $content = preg_replace('/on\w+="[^"]*"/i', '', $content);
        $content = preg_replace("/on\w+='[^']*'/i", '', $content);
        $content = preg_replace('/style="[^"]*"/i', '', $content);
        $content = preg_replace("/style='[^']*'/i", '', $content);
        $content = preg_replace('/(href|src)\s*=\s*"(javascript|data|vbscript):[^"]*"/i', '$1="#"', $content);
        $content = preg_replace("/(href|src)\s*=\s*'(javascript|data|vbscript):[^']*'/i", "$1='#'", $content);

        $allowed = '<p><br><strong><em><ul><ol><li><blockquote><div><h1><h2><h3><a><img>';
        $content = strip_tags($content, $allowed);

        $content = preg_replace_callback('/<a\s+([^>]+)>/i', function ($matches) {
            preg_match('/href=["\']([^"\']+)["\']/', $matches[1], $href);
            $url = $href[1] ?? '#';

            if (! preg_match('#^https?://#i', $url)) {
                $url = '#';
            }

            return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">';
        }, $content);

        $content = preg_replace_callback('/<img\s+([^>]+)>/i', function ($matches) {
            preg_match('/src=["\']([^"\']+)["\']/', $matches[1], $src);
            $url = $src[1] ?? '';

            if (! preg_match('#^https?://#i', $url)) {
                return '';
            }

            return '<img src="' . $url . '" alt="" />';
        }, $content);

        return preg_replace('/<(\w+)[^>]*>\s*<\/\1>/', '', $content);
    }

    private function generateUniqueSlug(string $title, ?Post $ignorePost = null): string
    {
        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = 'blog';
        }

        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $ignorePost)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?Post $ignorePost = null): bool
    {
        $query = Post::where('slug', $slug);

        if ($ignorePost) {
            $query->where('id', '!=', $ignorePost->id);
        }

        return $query->exists();
    }

}
