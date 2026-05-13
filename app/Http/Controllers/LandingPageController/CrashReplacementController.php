<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrashReplacement;

class CrashReplacementController extends Controller
{
    public function index()
    {
        $crashReplacements = CrashReplacement::orderBy('position')->get();
        return view('Admin.crashReplacements.index', compact('crashReplacements'));
    }

    public function create()
    {
        return view('Admin.crashReplacements.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['position'] = CrashReplacement::max('position') + 1;

        CrashReplacement::create($data);

        return redirect()->route('admin.crash-replacements.index')->with('success', 'Crash Replacement berhasil ditambahkan');
    }

    public function edit(CrashReplacement $crashReplacement)
    {
        return view('Admin.crashReplacements.form', compact('crashReplacement'));
    }

    public function update(Request $request, CrashReplacement $crashReplacement)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $crashReplacement->update($data);

        return redirect()->route('admin.crash-replacements.index')->with('success', 'Crash Replacement berhasil diupdate');
    }

    public function destroy(CrashReplacement $crashReplacement)
    {
        $crashReplacement->delete();
        return back()->with('success', 'Crash Replacement dihapus');
    }

    public function toggleStatus(CrashReplacement $crashReplacement)
    {
        $crashReplacement->update([
            'is_active' => ! $crashReplacement->is_active,
        ]);

        return back()->with('success', 'Status Crash Replacement berhasil diperbarui');
    }

    public function reorder(Request $request)
    {
       
        foreach ($request->order as $index => $id) {
            CrashReplacement::where('id', $id)->update([
                'position' => $index
            ]);
        }

        return response()->json(['success' => true]);
    }
}