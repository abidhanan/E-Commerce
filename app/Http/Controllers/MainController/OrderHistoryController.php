<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReview;
use App\Services\OrderLifecycleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderHistoryController extends Controller
{
    public function index(Request $request, OrderLifecycleService $lifecycleService): View
    {
        $lifecycleService->completeEstimatedShipments();

        $orders = Order::query()
            ->with(['items.product.images', 'items.productVariant'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(8);

        return view('Users.orders.index', compact('orders'));
    }

    public function show(Request $request, string $orderCode, OrderLifecycleService $lifecycleService): View
    {
        $lifecycleService->completeEstimatedShipments();

        $order = Order::query()
            ->with(['address', 'items.product.images', 'items.productVariant', 'review', 'complaints.photos'])
            ->where('user_id', $request->user()->id)
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('Users.orders.show', compact('order'));
    }

    public function complete(Request $request, string $orderCode, OrderLifecycleService $lifecycleService): RedirectResponse
    {
        $order = $this->findUserOrder($request, $orderCode);

        if ($order->status !== 'shipped') {
            throw ValidationException::withMessages([
                'status' => 'Pesanan hanya bisa diselesaikan saat statusnya sedang dikirim.',
            ]);
        }

        $lifecycleService->complete($order);

        return redirect()
            ->route('user.orders.show', $order->order_code)
            ->with('success', 'Pesanan ditandai selesai. Kamu bisa memberi rating atau mengirim komplain jika ada masalah.');
    }

    public function storeReview(\Illuminate\Http\Request $request, string $orderCode)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        // 2. Cari pesanan berdasarkan order_code dan pastikan itu milik user yang sedang login
        $order = \App\Models\Order::where('order_code', $orderCode)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // 3. Pertahanan Logika 1: Hanya pesanan selesai yang boleh diulas
        if ($order->status !== 'completed') {
            return back()->with('notify', [
                'type' => 'error',
                'title' => 'Ditolak',
                'message' => 'Anda hanya dapat memberikan ulasan pada pesanan yang sudah selesai.'
            ]);
        }

        // 4. Pertahanan Logika 2: Mencegah ulasan ganda
        if ($order->review()->exists()) {
            return back()->with('notify', [
                'type' => 'error',
                'title' => 'Ditolak',
                'message' => 'Pesanan ini sudah memiliki ulasan.'
            ]);
        }

        // 5. Eksekusi Penyimpanan
        \App\Models\OrderReview::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // 6. Kembalikan pengguna dengan notifikasi sukses
        return back()->with('notify', [
            'type' => 'success',
            'title' => 'Ulasan Berhasil',
            'message' => 'Terima kasih telah membagikan pengalaman Anda!'
        ]);
    }

    public function storeComplaint(Request $request, string $orderCode): RedirectResponse
    {
        $order = $this->findUserOrder($request, $orderCode);

        if (! in_array($order->status, ['shipped', 'completed'], true)) {
            throw ValidationException::withMessages([
                'complaint' => 'Komplain bisa dibuat saat pesanan sedang dikirim atau sudah selesai.',
            ]);
        }

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:4'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $order, $data) {
            $complaint = $order->complaints()->create([
                'user_id' => $request->user()->id,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'status' => 'submitted',
            ]);

            foreach ($request->file('photos', []) as $photo) {
                $complaint->photos()->create([
                    'path' => $photo->store('complaints', 'public'),
                ]);
            }
        });

        return redirect()
            ->route('user.orders.show', $order->order_code)
            ->with('success', 'Komplain berhasil dikirim. Admin akan memproses laporan kamu.');
    }

    private function findUserOrder(Request $request, string $orderCode): Order
    {
        return Order::query()
            ->where('user_id', $request->user()->id)
            ->where('order_code', $orderCode)
            ->firstOrFail();
    }
}
