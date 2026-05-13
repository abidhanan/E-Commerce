<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
        public function index()
        {
            $posts = Post::with(['category'])
                ->where('status', 'published')
                ->latest()
                ->paginate(10);
    
            return view('Users.posts.index', compact('posts'));
        }
    
        public function show($slug)
{
    $blog = Post::with(['category'])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    $relatedPosts = Post::with(['category'])
        ->where('id', '!=', $blog->id)
        ->where('status', 'published')
        ->where(function ($query) use ($blog) {
            $query->where('category_id', $blog->category_id);

            if (!empty($blog->tag_id)) {
                foreach ($blog->tag_id as $tagId) {
                    $query->orWhereJsonContains('tag_id', $tagId);
                }
            }
        })
        ->latest('published_at')
        ->take(3)
        ->get();

    return view('Users.posts.show', compact('blog', 'relatedPosts'));
}
}