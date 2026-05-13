<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('position')->get();
        return view('Admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('Admin.faqs.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['position'] = Faq::max('position') + 1;

        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan');
    }

    public function edit(Faq $faq)
    {
        return view('Admin.faqs.form', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diupdate');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ dihapus');
    }

    public function toggleStatus(Faq $faq)
    {
        $faq->update([
            'is_active' => ! $faq->is_active,
        ]);

        return back()->with('success', 'Status FAQ berhasil diperbarui');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Faq::where('id', $id)->update([
                'position' => $index
            ]);
        }

        return response()->json(['success' => true]);
    }
}
