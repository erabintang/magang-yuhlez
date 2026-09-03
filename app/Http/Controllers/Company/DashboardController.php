<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\InternshipProgram;
use App\Models\InternshipApplication;
use App\Models\Work;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $company = Auth::user()->companyProfile;

        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }

        $stats = [
            'total_programs' => InternshipProgram::where('company_id', $company->id)
                ->whereNull('deleted_at')->count(),
            'pending_applications' => InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->where('status', 'PENDING')
                ->whereNull('deleted_at')->count(),
            'accepted_interns' => InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->where('status', 'ACCEPTED')
                ->whereNull('deleted_at')->count(),
            'total_works' => Work::where('company_id', $company->id)
                ->whereNull('deleted_at')->count(),
        ];

        $recentApplications = InternshipApplication::with(['intern', 'program', 'position'])
            ->whereHas('program', fn($q) => $q->where('company_id', $company->id))
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
            'PENDING' => InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->where('status', 'PENDING')->whereNull('deleted_at')->count(),
            'ACCEPTED' => InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->where('status', 'ACCEPTED')->whereNull('deleted_at')->count(),
            'REJECTED' => InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->where('status', 'REJECTED')->whereNull('deleted_at')->count(),
            'CANCELLED' => InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
                ->where('status', 'CANCELLED')->whereNull('deleted_at')->count(),
        ];

        // Monthly registration trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyTrend[] = [
                'month' => $date->format('M Y'),
                'count' => InternshipApplication::whereHas('program', fn($q) => $q->where('company_id', $company->id))
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

        return view('dashboard.company.index', compact(
            'stats', 'recentApplications', 'recentNotifications',
            'statusCounts', 'monthlyTrend', 'activityFeed'
        ));
    }
}
