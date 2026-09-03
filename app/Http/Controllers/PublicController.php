<?php

namespace App\Http\Controllers;

use App\Models\InternshipProgram;
use App\Models\CompanyProfile;
use App\Models\InternProfile;
use App\Models\Work;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function programs(Request $request)
    {
        $query = InternshipProgram::select('id', 'slug', 'title', 'short_description', 'company_id', 'registration_end', 'registration_start')
            ->with(['company:id,name,slug', 'positions:id,program_id'])
            ->whereNull('deleted_at')
            ->where('registration_end', '>=', now());

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $programs = $query->latest()->paginate(12);

        return view('public.programs.index', compact('programs'));
    }

    public function programShow(string $slug)
    {
        $program = InternshipProgram::with([
            'company:id,name,slug,short_description,whatsapp,contact_email,address,gmap_embed',
            'positions:id,program_id,name,description,quota',
            'banners.file:id,storage_path',
        ])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('public.programs.show', compact('program'));
    }

    public function companies()
    {
        $companies = CompanyProfile::select('id', 'slug', 'name', 'short_description', 'logo_file_id', 'contact_email')
            ->withCount('programs')
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(12);

        return view('public.companies.index', compact('companies'));
    }

    public function companyShow(string $slug)
    {
        $company = CompanyProfile::with([
            'user:id,name',
            'programs' => function ($q) {
                $q->select('id', 'slug', 'title', 'short_description', 'company_id', 'registration_end')
                    ->whereNull('deleted_at')
                    ->where('registration_end', '>=', now())
                    ->latest();
            },
            'works' => function ($q) {
                $q->select('id', 'slug', 'title', 'short_description', 'company_id', 'category', 'year', 'is_published')
                    ->whereNull('deleted_at')
                    ->where('is_published', true)
                    ->latest();
            },
        ])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('public.companies.show', compact('company'));
    }

    public function works(Request $request)
    {
        $query = Work::select('id', 'slug', 'title', 'short_description', 'work_type', 'company_id', 'poster_file_id', 'category', 'year', 'created_at')
            ->with([
                'company:id,name,slug',
                'poster:id,storage_path',
            ])
            ->whereNull('deleted_at')
            ->where('is_published', true);

        if ($request->has('type') && in_array($request->type, ['PROGRAM_WORK', 'PUBLIC_WORK'])) {
            $query->where('work_type', $request->type);
        }

        if ($request->has('kategori') && $request->kategori) {
            $query->where('category', $request->kategori);
        }

        $works = $query->latest()->paginate(12);

        return view('public.works.index', compact('works'));
    }

    public function workShow(string $slug)
    {
        $work = Work::with([
            'company:id,name,slug,short_description',
            'poster:id,storage_path',
            'gallery.file:id,storage_path',
            'interns.intern:id,name,slug',
        ])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('public.works.show', compact('work'));
    }

    public function internShow(string $slug)
    {
        $intern = InternProfile::with([
            'user:id,name',
            'photo:id,storage_path',
        ])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Load accepted programs with company (max 50 per intern, no need to paginate public profile)
        $programs = $intern->applications()
            ->where('status', 'ACCEPTED')
            ->whereNull('deleted_at')
            ->with(['program.company', 'position'])
            ->orderByDesc('applied_at')
            ->limit(50)
            ->get()
            ->pluck('program')
            ->unique('id')
            ->values();

        // Load works intern participates in (max 50)
        $works = $intern->works()
            ->whereNull('work_interns.deleted_at')
            ->where('works.is_published', true)
            ->with(['company:id,name,slug', 'poster:id,storage_path'])
            ->latest()
            ->limit(50)
            ->get();

        return view('public.interns.show', compact('intern', 'programs', 'works'));
    }


}
