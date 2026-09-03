<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', $userId)
            ->whereNull('deleted_at')
            ->where('is_read', false)
            ->count();

        return view('dashboard.root.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markRead(string $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
