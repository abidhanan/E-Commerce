<?php

namespace App\Http\Controllers\BlogController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagBlog as Tag;
class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::all();
        return view('Admin.Blogs.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('Admin.Blogs.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tag_blogs,name,'.$request->id,
        ]);

        Tag::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        return view('Admin.Blogs.tags.edit', compact('tag'));
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

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->back()->with('success', 'Tag deleted successfully.');
    }
}