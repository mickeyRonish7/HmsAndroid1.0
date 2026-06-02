<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $query = auth()->user()->notifications()->latest();

        // Filter by type if provided
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->paginate(20);
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();
        
        // Clear cache
        $userId = auth()->id();
        Cache::forget("user:{$userId}:unread_notification_count");
        Cache::forget("user:{$userId}:recent_notifications");

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $count = $this->notificationService->markAllAsRead(auth()->id());
        
        // Clear cache
        $userId = auth()->id();
        Cache::forget("user:{$userId}:unread_notification_count");
        Cache::forget("user:{$userId}:recent_notifications");

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        }

        return redirect()->back()->with('success', "{$count} notifications marked as read");
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->delete();
        
        // Clear cache
        $userId = auth()->id();
        Cache::forget("user:{$userId}:unread_notification_count");
        Cache::forget("user:{$userId}:recent_notifications");

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notification deleted');
    }

    /**
     * Get unread notifications count (for AJAX)
     * Uses cache to reduce database queries
     */
    public function unreadCount()
    {
        $userId = auth()->id();
        $cacheKey = "user:{$userId}:unread_notification_count";
        
        // Try to get from cache first (cached for 30 seconds)
        $count = Cache::remember($cacheKey, 30, function () {
            return auth()->user()->unreadNotifications()->count();
        });
        
        return response()->json(['unread_count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown)
     * Uses cache to reduce database queries
     */
    public function recent()
    {
        $userId = auth()->id();
        $cacheKey = "user:{$userId}:recent_notifications";
        
        // Try to get from cache first (cached for 30 seconds)
        $notifications = Cache::remember($cacheKey, 30, function () {
            return auth()->user()
                ->notifications()
                ->latest()
                ->take(5)
                ->get();
        });

        return response()->json(['notifications' => $notifications]);
    }
}
