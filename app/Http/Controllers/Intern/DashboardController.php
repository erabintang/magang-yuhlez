<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\Certificate;
use App\Models\Notification;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $intern = $user->internProfile;

        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil intern terlebih dahulu.');
        }

        $stats = [
            'total_applications' => InternshipApplication::where('intern_id', $intern->id)
                ->whereNull('deleted_at')->count(),
            'pending_applications' => InternshipApplication::where('intern_id', $intern->id)
                ->where('status', 'PENDING')
                ->whereNull('deleted_at')->count(),
            'accepted_applications' => InternshipApplication::where('intern_id', $intern->id)
                ->where('status', 'ACCEPTED')
                ->whereNull('deleted_at')->count(),
            'total_certificates' => Certificate::where('intern_id', $intern->id)
                ->whereNull('deleted_at')->count(),
        ];

        $recentApplications = InternshipApplication::with(['program', 'position'])
            ->where('intern_id', $intern->id)
            ->whereNull('deleted_at')
            ->orderByDesc('applied_at')
            ->paginate(10)
            ->withQueryString();

        $recentNotifications = Notification::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        // Application status distribution (for doughnut chart)
        $statusCounts = [
            'PENDING' => InternshipApplication::where('intern_id', $intern->id)
                ->where('status', 'PENDING')->whereNull('deleted_at')->count(),
            'ACCEPTED' => InternshipApplication::where('intern_id', $intern->id)
                ->where('status', 'ACCEPTED')->whereNull('deleted_at')->count(),
            'REJECTED' => InternshipApplication::where('intern_id', $intern->id)
                ->where('status', 'REJECTED')->whereNull('deleted_at')->count(),
            'CANCELLED' => InternshipApplication::where('intern_id', $intern->id)
                ->where('status', 'CANCELLED')->whereNull('deleted_at')->count(),
        ];

        // Monthly application trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyTrend[] = [
                'month' => $date->format('M Y'),
                'count' => InternshipApplication::where('intern_id', $intern->id)
                    ->whereNull('deleted_at')
                    ->whereYear('applied_at', $date->year)
                    ->whereMonth('applied_at', $date->month)
                    ->count(),
            ];
        }

        // Recent activity feed (latest 8 notifications)
        $activityFeed = Notification::where('user_id', Auth::id())
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('dashboard.intern.index', compact(
            'intern', 'stats', 'recentApplications', 'recentNotifications',
            'statusCounts', 'monthlyTrend', 'activityFeed'
        ));
    }

    public function notifications()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->where('is_read', false)
            ->count();

        return view('dashboard.intern.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markNotificationRead(string $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllNotificationsRead()
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

    public function works()
    {
        $intern = Auth::user()->internProfile;

        // Karya yang diikuti (dari company)
        $works = Work::whereHas('interns', fn($q) => $q->where('intern_id', $intern->id))
            ->with(['company:id,name,slug', 'gallery.file:id,storage_path'])
            ->whereNull('deleted_at')
            ->where('is_published', true)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Karya yang dibuat sendiri oleh intern
        $ownWorks = Work::whereHas('interns', fn($q) => $q->where('intern_id', $intern->id))
            ->whereNull('company_id')
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return view('dashboard.intern.works.index', compact('works', 'ownWorks'));
    }

}
