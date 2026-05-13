<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderComplaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $complaints = OrderComplaint::query()
            ->with(['order.user', 'user', 'photos'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('Admin.order-complaints.index', compact('complaints', 'status'));
    }

    public function show(OrderComplaint $complaint): View
    {
        $complaint->load(['order.user', 'order.items.product', 'order.items.productVariant', 'order.address', 'user', 'photos']);

        return view('Admin.order-complaints.show', compact('complaint'));
    }

    public function update(Request $request, OrderComplaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['submitted', 'in_review', 'resolved', 'rejected'])],
            'admin_response' => ['nullable', 'string', 'max:2000'],
        ]);

        $complaint->update([
            'status' => $data['status'],
            'admin_response' => $data['admin_response'] ?? null,
            'resolved_at' => $data['status'] === 'resolved' ? ($complaint->resolved_at ?? now()) : null,
        ]);

        return redirect()
            ->route('admin.order-complaints.show', $complaint)
            ->with('success', 'Status komplain berhasil diperbarui.');
    }
}
