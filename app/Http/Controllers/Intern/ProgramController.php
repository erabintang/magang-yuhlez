<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\InternshipProgram;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        // Spec: "Melihat Program Magang Yang Sedang Berjalan/Selesai/Tersedia"
        // Show ALL non-deleted programs, not just available ones
        $query = InternshipProgram::with(['company', 'positions'])
            ->whereNull('deleted_at');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $programs = $query->orderByDesc('created_at')->paginate(12);

        return view('dashboard.intern.programs.index', compact('programs'));
    }

    public function show(string $slug)
    {
        $program = InternshipProgram::with(['company', 'positions', 'banners.file'])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $user = Auth::user();
        $intern = $user->internProfile;

        // Check if already applied
        $existingApplication = null;
        if ($intern) {
            $existingApplication = InternshipApplication::where('intern_id', $intern->id)
                ->where('program_id', $program->id)
                ->whereNull('deleted_at')
                ->whereIn('status', ['PENDING', 'ACCEPTED'])
                ->first();
        }

        return view('dashboard.intern.programs.show', compact('program', 'existingApplication'));
    }
}
