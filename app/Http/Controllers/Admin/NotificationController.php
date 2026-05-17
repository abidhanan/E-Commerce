<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderComplaint;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $readAt = $request->date('read_at') ?? now()->subDays(30);
        $items = collect();

        if ($user?->can('manage orders')) {
            $items = $items
                ->merge($this->orderNotifications())
                ->merge($this->complaintNotifications());
        }

        if ($user?->can('manage users')) {
            $items = $items->merge($this->userNotifications());
        }

        if ($user?->hasRole('superadmin')) {
            $items = $items->merge($this->activityNotifications());
        }

        $items = $items
            ->sortByDesc('timestamp')
            ->take(12)
            ->values()
            ->map(function (array $item) use ($readAt) {
                $createdAt = $item['timestamp'];

                return array_merge($item, [
                    'created_at' => $createdAt->toIso8601String(),
                    'time_label' => $createdAt->diffForHumans(),
                    'is_unread' => $createdAt->greaterThan($readAt),
                ]);
            });

        return response()->json([
            'items' => $items,
            'unread_count' => $items->where('is_unread', true)->count(),
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    private function orderNotifications()
    {
        return Order::query()
            ->with('user:id,name,email')
            ->whereIn('status', ['pending', 'waiting_admin', 'paid', 'quoted'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(function (Order $order) {
                $status = str_replace('_', ' ', $order->status);

                return [
                    'id' => 'order-' . $order->id . '-' . $order->updated_at?->timestamp,
                    'type' => 'Order',
                    'icon' => 'bi-receipt',
                    'title' => 'Order ' . $order->order_code,
                    'message' => trim(($order->user?->name ?? 'Customer') . ' - ' . ucfirst($status)),
                    'url' => route('admin.orders.show', $order, false),
                    'severity' => in_array($order->status, ['pending', 'waiting_admin'], true) ? 'gold' : 'dark',
                    'timestamp' => $order->updated_at ?? $order->created_at,
                ];
            });
    }

    private function complaintNotifications()
    {
        return OrderComplaint::query()
            ->with(['order:id,order_code', 'user:id,name,email'])
            ->whereIn('status', ['submitted', 'in_review'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(function (OrderComplaint $complaint) {
                return [
                    'id' => 'complaint-' . $complaint->id . '-' . $complaint->updated_at?->timestamp,
                    'type' => 'Complaint',
                    'icon' => 'bi-exclamation-circle',
                    'title' => $complaint->subject,
                    'message' => ($complaint->user?->name ?? 'Customer') . ' - ' . ($complaint->order?->order_code ?? 'Order'),
                    'url' => route('admin.order-complaints.show', $complaint, false),
                    'severity' => 'gold',
                    'timestamp' => $complaint->updated_at ?? $complaint->created_at,
                ];
            });
    }

    private function userNotifications()
    {
        return User::query()
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'email', 'created_at', 'updated_at'])
            ->map(function (User $user) {
                return [
                    'id' => 'user-' . $user->id . '-' . $user->created_at?->timestamp,
                    'type' => 'User',
                    'icon' => 'bi-person-plus',
                    'title' => 'User baru',
                    'message' => $user->name . ' - ' . $user->email,
                    'url' => route('admin.users.edit', $user, false),
                    'severity' => 'dark',
                    'timestamp' => $user->created_at,
                ];
            });
    }

    private function activityNotifications()
    {
        return ActivityLog::query()
            ->with('user:id,name,email')
            ->where('event', '!=', 'login')
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (ActivityLog $log) {
                return [
                    'id' => 'activity-' . $log->id . '-' . $log->created_at?->timestamp,
                    'type' => 'Activity',
                    'icon' => 'bi-activity',
                    'title' => ucfirst(str_replace('_', ' ', $log->event)),
                    'message' => ($log->user?->name ?? 'System') . ' updated admin data',
                    'url' => route('admin.performance.index', [], false),
                    'severity' => 'dark',
                    'timestamp' => $log->created_at,
                ];
            });
    }
}
