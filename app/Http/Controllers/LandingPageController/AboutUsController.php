<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aboutus;
use App\Support\HtmlSanitizer;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = Aboutus::first();
        return view('Admin.about.index', compact('about'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $cleanContent = $this->sanitizeHtml($validated['content']);

        Aboutus::updateOrCreate(
            ['id' => 1],
            [
                'title'   => $validated['title'],
                'content' => $cleanContent,
            ]
        );

        return back()->with('success', 'About Us berhasil disimpan');
    }
   
    /**
     * 🔐 Sanitasi HTML (lebih aman & sesuai Trix tanpa image)
     */
    private function sanitizeHtml($html)
    {
        return HtmlSanitizer::clean(
            (string) $html,
            ['p', 'br', 'b', 'strong', 'i', 'em', 'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3', 'h4', 'blockquote'],
        );
    }
}
