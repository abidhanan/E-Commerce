<?php

namespace App\Http\Controllers\BlogController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagBlog as Tag;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::latest()->get();

        return view('SuperAdmin.blog.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('SuperAdmin.blog.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tag_blogs,name',
        ]);

        Tag::create([
            'name' => trim($request->name),
        ]);

        return redirect()->route('superadmin.tags.index')
            ->with('success', 'Tag berhasil dibuat.');
    }

    public function edit(Tag $tag)
    {
        return view('SuperAdmin.blog.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tag_blogs,name,' . $tag->id,
        ]);

        $name = trim($request->name);

        $tag->update([
            'name' => $name,
        ]);

        return redirect()->route('superadmin.tags.index')
            ->with('success', 'Tag berhasil diperbarui.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('superadmin.tags.index')
            ->with('success', 'Tag berhasil dihapus.');
    }
}
