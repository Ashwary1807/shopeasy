<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    /**
     * Display a paginated list of admin notifications.
     */
    public function index(Request $request): View
    {
        $filter = strtolower(trim((string) $request->query('filter', 'all')));
        $search = trim((string) $request->query('search', ''));

        $query = AdminNotification::latest('id');

        if ($filter === 'unread') {
            $query->unread();
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                    ->orWhere('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        $notifications = $query->paginate(15)->withQueryString();

        $unreadCount = AdminNotification::unread()->count();
        $totalCount = AdminNotification::count();

        return view('admin.notifications.index', compact(
            'notifications',
            'filter',
            'search',
            'unreadCount',
            'totalCount'
        ));
    }

    /**
     * Mark a single notification as read and optionally navigate to the order.
     */
    public function markAsRead(int $id, Request $request): RedirectResponse|JsonResponse
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();

        // If AJAX request, return json response with updated unread counter
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => AdminNotification::unread()->count(),
                'notification_id' => $notification->id,
            ]);
        }

        // If redirect to order was requested (e.g. from notification bell click)
        if ($request->boolean('redirect_to_order') && $notification->order_number) {
            return redirect()->route('admin.orders.show', $notification->order_number);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllRead(Request $request): RedirectResponse|JsonResponse
    {
        $updated = AdminNotification::unread()->update([
            'read_at' => now(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'marked_count' => $updated,
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }
}
