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

        // Membatasi maksimal 5 banner
        $count = DisplayLogin::count();
        if ($count >= 5) {
            return back()
                ->withErrors(['image_path' => 'Slot Banner Penuh! Maksimal hanya 5 gambar. Hapus salah satu terlebih dahulu.'])
                ->withInput();
        }

        $data = [
            'label' => $request->label,
            'position' => (DisplayLogin::max('position') ?? 0) + 1,
            'is_active' => true, // Mengatur default menjadi aktif
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
            // Hapus gambar lama
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

    // Fungsi tambahan untuk mengubah status banner (Aktif / Nonaktif)
    public function toggleStatus(DisplayLogin $displayLogin)
    {
        $displayLogin->update([
            'is_active' => !$displayLogin->is_active
        ]);

        return back()->with('success', 'Status banner berhasil diubah.');
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
        
        return redirect()
            ->route('admin.display-logins.index')
            ->with('success', 'Display Login deleted successfully.');
    }
}