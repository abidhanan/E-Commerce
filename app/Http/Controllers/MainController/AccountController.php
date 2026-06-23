<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DisplayLogin;
class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $addresses = $user->addresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('id')
            ->get();
            
        $display = \App\Models\DisplayLogin::inRandomOrder()->get();
        $address = $addresses->firstWhere('is_primary', true) ?? $addresses->first();

        // =========================================================
        // Kueri Mutlak: Tarik ulasan user beserta relasi produknya
        // =========================================================
        $reviews = \App\Models\OrderReview::query()
            ->with(['order.items.product' => function($query) {
                $query->with(['images' => fn($q) => $q->where('is_primary', true)]);
            }, 'order.items.productVariant'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('Users.account.index', compact('user', 'address', 'addresses', 'display', 'reviews'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $user->update($data);

        return redirect()
            ->route('account.index')
            ->with('notify', [
                'type' => 'success',
                'title' => 'Profil',
                'message' => 'Profil berhasil diperbarui.',
            ]);
    }

    public function show($id)
    {
        return response()->json($this->findUserAddress($id));
    }

    public function store(Request $request)
    {
        if (Auth::user()->addresses()->count() >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal 3 alamat per akun.',
            ], 422);
        }

        $data = $this->validateAddress($request);
        $data['user_id'] = Auth::id();
        $data['is_primary'] = ! Auth::user()->addresses()->exists();

        $address = Address::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan.',
            'address' => $address,
        ]);
    }

    public function updateAddress(Request $request, $id)
    {
        $address = $this->findUserAddress($id);
        $address->update($this->validateAddress($request));

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        $address = $this->findUserAddress($id);
        $wasPrimary = (bool) $address->is_primary;

        $address->delete();

        if ($wasPrimary) {
            Auth::user()->addresses()
                ->latest('id')
                ->first()
                ?->update(['is_primary' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus.',
        ]);
    }

    public function setPrimary($id)
    {
        $address = $this->findUserAddress($id);

        Address::where('user_id', Auth::id())->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat utama berhasil diperbarui.',
        ]);
    }

    public function verifyPassword(Request $request)
    {
        $request->validate(['current_password' => 'required']);

        if (!\Hash::check($request->current_password, $request->user()->password)) {
            return response()->json(['success' => false, 'message' => 'Password salah.'], 422);
        }

        return response()->json(['success' => true]);
    }

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:30'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'full_address' => ['required', 'string', 'max:2000'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'note' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    }

    private function findUserAddress($id): Address
    {
        return Auth::user()
            ->addresses()
            ->findOrFail($id);
    }
}