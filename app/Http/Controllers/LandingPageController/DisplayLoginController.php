<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DisplayLogin;
use Illuminate\Support\Facades\Storage;
class DisplayLoginController extends Controller
{
    public function index()
    {
        $displayLogins = DisplayLogin::orderBy('position')->get();
        return view('Admin.DisplayLogin.index', compact('displayLogins'));
    }

    public function create()
    {
        return view('Admin.DisplayLogin.form');
    }
   public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'image_path' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'label' => $request->label,
            'position' => (DisplayLogin::max('position') ?? 0) + 1,
        ];

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store(
                'display_logins',
                'public'
            );

            $data['image_path'] = $path;
        }

        DisplayLogin::create($data);

        return redirect()
            ->route('admin.display-logins.index')
            ->with('success', 'Banner login berhasil ditambahkan.');
    }

    public function edit(DisplayLogin $displayLogin)
    {
        return view('Admin.DisplayLogin.form', compact('displayLogin'));
    }

    public function update(Request $request, DisplayLogin $displayLogin)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $data = [
            'label' => $request->label,
        ];
        if ($request->hasFile('image_path')) {
            if ($displayLogin->image_path) {
                Storage::disk('public')->delete($displayLogin->image_path);
            }
            $path = $request->file('image_path')->store(
                'display_logins',
                'public'
            );
            $data['image_path'] = $path;
        }
        $displayLogin->update($data);
        return redirect()
            ->route('admin.display-logins.index')
            ->with('success', 'Banner login berhasil diperbarui.');
    }

     public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            DisplayLogin::where('id', $id)->update([
                'position' => $index
            ]);
        }

        return response()->json(['success' => true]);
    }
    public function destroy(DisplayLogin $displayLogin)
    {
        if ($displayLogin->image_path) {
            Storage::disk('public')->delete($displayLogin->image_path);
        }
        $displayLogin->delete();
        return redirect()->route('admin.display-logins.index')->with('success', 'Display Login deleted successfully.');
    }
}