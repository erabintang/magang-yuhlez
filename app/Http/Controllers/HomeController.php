<?php

namespace App\Http\Controllers;

use App\Models\InternshipProgram;
use App\Models\CompanyProfile;
use App\Models\Work;
use App\Models\HomepageSection;

class HomeController extends Controller
{
    public function index()
    {
        // Optimized: limited queries, eager loading, minimal columns
        $featuredPrograms = InternshipProgram::select(
            'id', 'slug', 'title', 'short_description', 'company_id', 'registration_end'
        )
            ->with(['company:id,name,slug', 'positions:id,program_id'])
            ->whereNull('deleted_at')
            ->where('registration_end', '>=', now())
            ->latest()
            ->limit(6)
            ->get();

        $featuredCompanies = CompanyProfile::select('id', 'slug', 'name', 'short_description', 'logo_file_id')
            ->whereNull('deleted_at')
            ->withCount('programs')
            ->latest()
            ->limit(6)
            ->get();

        $featuredWorks = Work::select(
            'id', 'slug', 'title', 'short_description', 'description', 'work_type',
            'company_id', 'poster_file_id', 'category', 'year',
            'source_code_url', 'deploy_url'
        )
            ->with([
                'company:id,name,slug',
                'interns' => function ($q) {
                    $q->whereNull('work_interns.deleted_at')
                      ->with('intern:id,name,slug');
                },
                'gallery.file:id,storage_path',
            ])
            ->whereNull('deleted_at')
            ->where('is_published', true)
            ->latest()
            ->limit(8)
            ->get();

        // Homepage sections from CMS
        $homepageSections = HomepageSection::active()->ordered()->get()->keyBy('section_key');

        return view('home', compact('featuredPrograms', 'featuredCompanies', 'featuredWorks', 'homepageSections'));
    }
}
