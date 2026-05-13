<?php

namespace Tests\Feature;

use App\Models\CategoryBlog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostShowPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_show_renders_sanitized_trix_content_with_rich_elements(): void
    {
        $author = User::factory()->create();
        $category = CategoryBlog::create([
            'name' => 'Journal',
            'slug' => 'journal',
        ]);

        $post = Post::create([
            'user_id' => $author->id,
            'category_id' => $category->id,
            'title' => 'Cold Weather Notes',
            'slug' => 'cold-weather-notes',
            'excerpt' => 'Field guide.',
            'content' => '<h2>Field Notes</h2><blockquote><div>Move slow in cold weather.</div></blockquote><figure><img src="https://example.com/photo.jpg" alt="Peak"><figcaption>Morning ridge</figcaption></figure><pre><code>pack();</code></pre><script>alert("x")</script>',
            'status' => 'published',
            'published_at' => now(),
            'tag_id' => [],
        ]);

        $response = $this->get(route('post.show', $post->slug));

        $response
            ->assertOk()
            ->assertSee('<h2>Field Notes</h2>', false)
            ->assertSee('<blockquote><div>Move slow in cold weather.</div></blockquote>', false)
            ->assertSee('<figcaption>Morning ridge</figcaption>', false)
            ->assertSee('<pre><code>pack();</code></pre>', false)
            ->assertDontSee('alert("x")', false);
    }
}
