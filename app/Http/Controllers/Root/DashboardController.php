<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\CompanyProfile;
use App\Models\InternProfile;
use App\Models\InternshipProgram;
use App\Models\InternshipApplication;
use App\Models\Work;
use App\Models\WorkGallery;
use App\Models\Certificate;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_companies' => CompanyProfile::whereNull('deleted_at')->count(),
            'total_interns' => InternProfile::whereNull('deleted_at')->count(),
            'active_programs' => InternshipProgram::whereNull('deleted_at')
                ->where('registration_end', '>=', now())
                ->count(),
            'total_applications' => InternshipApplication::whereNull('deleted_at')->count(),
            'pending_applications' => InternshipApplication::whereNull('deleted_at')
                ->where('status', 'PENDING')
                ->count(),
            'total_certificates' => Certificate::whereNull('deleted_at')->count(),
        ];

        $recentApplications = InternshipApplication::with(['intern', 'program', 'position'])
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
            'PENDING' => InternshipApplication::whereNull('deleted_at')->where('status', 'PENDING')->count(),
            'ACCEPTED' => InternshipApplication::whereNull('deleted_at')->where('status', 'ACCEPTED')->count(),
            'REJECTED' => InternshipApplication::whereNull('deleted_at')->where('status', 'REJECTED')->count(),
            'CANCELLED' => InternshipApplication::whereNull('deleted_at')->where('status', 'CANCELLED')->count(),
        ];

        // Monthly registration trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyTrend[] = [
                'month' => $date->format('M Y'),
                'count' => InternshipApplication::whereNull('deleted_at')
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

        return view('dashboard.root.index', compact(
            'stats', 'recentApplications', 'recentNotifications',
            'statusCounts', 'monthlyTrend', 'activityFeed'
        ));
    }

    public function companies()
    {
        $companies = CompanyProfile::with(['user', 'logo'])
            ->whereNull('deleted_at')
            ->withCount('programs')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('dashboard.root.companies', compact('companies'));
    }

    public function companyShow(string $slug)
    {
        $company = CompanyProfile::with([
            'user:id,name,email',
            'logo:id,storage_path',
            'programs' => function ($q) {
                $q->whereNull('deleted_at')->latest();
            },
        ])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $stats = [
            'total_programs' => $company->programs()->whereNull('deleted_at')->count(),
            'total_interns' => $company->programs()->whereHas('programInterns')->count(),
            'active_programs' => $company->programs()->whereNull('deleted_at')
                ->where('registration_end', '>=', now())->count(),
        ];

        return view('dashboard.root.companies.show', compact('company', 'stats'));
    }

    public function interns()
    {
        $interns = InternProfile::with(['user', 'photo'])
            ->whereNull('deleted_at')
            ->withCount('applications')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('dashboard.root.interns', compact('interns'));
    }

    public function internShow(string $slug)
    {
        $intern = InternProfile::with([
            'user:id,name,email',
            'photo:id,storage_path',
            'cv:id,storage_path',
        ])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $applications = $intern->applications()
            ->with(['program.company', 'position'])
            ->whereNull('deleted_at')
            ->orderByDesc('applied_at')
            ->paginate(10)
            ->withQueryString();

        $certificates = $intern->certificates()
            ->with(['program.company'])
            ->whereNull('deleted_at')
            ->orderByDesc('issued_at')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.root.interns.show', compact('intern', 'applications', 'certificates'));
    }

    public function works(Request $request)
    {
        $query = Work::with(['company:id,name,slug'])
            ->whereNull('deleted_at');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('company', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->has('type') && in_array($request->type, ['PROGRAM_WORK', 'PUBLIC_WORK'])) {
            $query->where('work_type', $request->type);
        }

        $works = $query->latest()->paginate(20);

        return view('dashboard.root.works', compact('works'));
    }

    public function workShow(string $slug)
    {
        $work = Work::with([
            'company:id,name,slug',
            'gallery.file:id,storage_path',
            'interns.intern:id,name,slug',
        ])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.root.works_show', compact('work'));
    }

    public function workToggle(string $slug)
    {
        $work = Work::where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $work->update([
            'is_published' => !$work->is_published,
            'published_at' => !$work->is_published ? now() : null,
        ]);

        $status = $work->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Karya berhasil {$status}.");
    }

    public function workDestroy(string $slug)
    {
        $work = Work::where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $work->delete();

        return redirect()->route('root.works.index')
            ->with('success', 'Karya berhasil dihapus.');
    }

    public function companyEdit(string $slug)
    {
        $company = CompanyProfile::with(['user:id,name,email'])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.root.companies.edit', compact('company'));
    }

    public function internEdit(string $slug)
    {
        $intern = InternProfile::with(['user:id,name,email'])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.root.interns.edit', compact('intern'));
    }

    public function workUpdate(Request $request, string $slug)
    {
        $work = Work::where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
            'is_published' => 'boolean',
        ]);

        $work->update($validated);

        return redirect()->route('root.works.show', $slug)
            ->with('success', 'Karya berhasil diperbarui.');
    }
}
