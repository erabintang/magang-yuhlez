<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomepageController extends Controller
{
    /**
     * List all homepage sections
     */
    public function index()
    {
        $sections = HomepageSection::whereNull('deleted_at')
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        return view('dashboard.root.homepage.index', compact('sections'));
    }

    /**
     * Edit a specific section
     */
    public function edit(string $key)
    {
        $section = HomepageSection::where('section_key', $key)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.root.homepage.edit', compact('section'));
    }

    /**
     * Update section content
     */
    public function update(Request $request, string $key)
    {
        $section = HomepageSection::where('section_key', $key)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $section->update([
            'title' => $validated['title'] ?? $section->title,
            'is_active' => $validated['is_active'] ?? $section->is_active,
            'sort_order' => $validated['sort_order'] ?? $section->sort_order,
        ]);

        return redirect()->route('root.homepage.index')
            ->with('success', "Section \"{$section->section_key}\" berhasil diperbarui.");
    }

    /**
     * Update hero section content
     */
    public function updateHero(Request $request)
    {
        $section = HomepageSection::getSection('hero');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cta_primary_text' => 'nullable|string|max:100',
            'cta_primary_url' => 'nullable|string|max:255',
            'cta_secondary_text' => 'nullable|string|max:100',
            'cta_secondary_url' => 'nullable|string|max:255',
        ]);

        $content = [
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? '',
            'description' => $validated['description'] ?? '',
            'cta_primary_text' => $validated['cta_primary_text'] ?? '',
            'cta_primary_url' => $validated['cta_primary_url'] ?? '',
            'cta_secondary_text' => $validated['cta_secondary_text'] ?? '',
            'cta_secondary_url' => $validated['cta_secondary_url'] ?? '',
        ];

        if ($section) {
            $section->update(['content' => $content]);
        } else {
            HomepageSection::create([
                'section_key' => 'hero',
                'title' => 'Hero',
                'content' => $content,
                'sort_order' => 0,
            ]);
        }

        return redirect()->route('root.homepage.edit', 'hero')
            ->with('success', 'Hero section berhasil diperbarui.');
    }

    /**
     * Update about section content
     */
    public function updateAbout(Request $request)
    {
        $section = HomepageSection::getSection('about');

        $validated = $request->validate([
            'subtitle' => 'nullable|string|max:255',
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'vision' => 'nullable|string|max:1000',
            'mission_items' => 'nullable|array',
            'mission_items.*' => 'nullable|string|max:500',
        ]);

        $content = [
            'subtitle' => $validated['subtitle'] ?? '',
            'heading' => $validated['heading'],
            'description' => $validated['description'] ?? '',
            'vision' => $validated['vision'] ?? '',
            'mission_items' => $validated['mission_items'] ?? [],
        ];

        if ($section) {
            $section->update(['content' => $content]);
        } else {
            HomepageSection::create([
                'section_key' => 'about',
                'title' => 'Tentang',
                'content' => $content,
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('root.homepage.edit', 'about')
            ->with('success', 'About section berhasil diperbarui.');
    }

    /**
     * Update team section - add/remove members
     */
    public function updateTeam(Request $request)
    {
        $section = HomepageSection::getSection('team');
        $items = $section?->items() ?? [];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'focus' => 'nullable|string|max:255',
            'photo' => 'nullable|string|max:255',
        ]);

        $items[] = [
            'name' => $validated['name'],
            'role' => $validated['role'],
            'focus' => $validated['focus'] ?? '',
            'photo' => $validated['photo'] ?? '',
        ];

        $content = ['items' => $items];

        if ($section) {
            $section->update(['content' => $content]);
        } else {
            HomepageSection::create([
                'section_key' => 'team',
                'title' => 'Tim',
                'content' => $content,
                'sort_order' => 2,
            ]);
        }

        return redirect()->route('root.homepage.edit', 'team')
            ->with('success', "Tim \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /**
     * Remove a team member
     */
    public function removeTeamMember(Request $request)
    {
        $section = HomepageSection::getSection('team');
        if (!$section) return back()->with('error', 'Section team tidak ditemukan.');

        $items = $section->items();
        $index = (int) $request->input('index', -1);

        if ($index >= 0 && $index < count($items)) {
            $removed = $items[$index]['name'] ?? 'Member';
            array_splice($items, $index, 1);
            $section->update(['content' => ['items' => $items]]);
            return back()->with('success', "\"{$removed}\" berhasil dihapus dari tim.");
        }

        return back()->with('error', 'Index tidak valid.');
    }

    /**
     * Update services section - add/remove services
     */
    public function updateService(Request $request)
    {
        $section = HomepageSection::getSection('services');
        $items = $section?->items() ?? [];

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $items[] = [
            'title' => $validated['title'],
            'description' => $validated['description'],
        ];

        $content = ['items' => $items];

        if ($section) {
            $section->update(['content' => $content]);
        } else {
            HomepageSection::create([
                'section_key' => 'services',
                'title' => 'Layanan',
                'content' => $content,
                'sort_order' => 3,
            ]);
        }

        return redirect()->route('root.homepage.edit', 'services')
            ->with('success', "Layanan \"{$validated['title']}\" berhasil ditambahkan.");
    }

    /**
     * Remove a service
     */
    public function removeService(Request $request)
    {
        $section = HomepageSection::getSection('services');
        if (!$section) return back()->with('error', 'Section services tidak ditemukan.');

        $items = $section->items();
        $index = (int) $request->input('index', -1);

        if ($index >= 0 && $index < count($items)) {
            $removed = $items[$index]['title'] ?? 'Service';
            array_splice($items, $index, 1);
            $section->update(['content' => ['items' => $items]]);
            return back()->with('success', "\"{$removed}\" berhasil dihapus.");
        }

        return back()->with('error', 'Index tidak valid.');
    }

    /**
     * Update contributors section - add/remove
     */
    public function updateContributor(Request $request)
    {
        $section = HomepageSection::getSection('contributors');
        $items = $section?->items() ?? [];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
        ]);

        $items[] = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
            'url' => $validated['url'] ?? '',
            'logo' => $validated['logo'] ?? '',
        ];

        $content = ['items' => $items];

        if ($section) {
            $section->update(['content' => $content]);
        } else {
            HomepageSection::create([
                'section_key' => 'contributors',
                'title' => 'Kontributor',
                'content' => $content,
                'sort_order' => 5,
            ]);
        }

        return redirect()->route('root.homepage.edit', 'contributors')
            ->with('success', "Kontributor \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /**
     * Remove a contributor
     */
    public function removeContributor(Request $request)
    {
        $section = HomepageSection::getSection('contributors');
        if (!$section) return back()->with('error', 'Section contributors tidak ditemukan.');

        $items = $section->items();
        $index = (int) $request->input('index', -1);

        if ($index >= 0 && $index < count($items)) {
            $removed = $items[$index]['name'] ?? 'Contributor';
            array_splice($items, $index, 1);
            $section->update(['content' => ['items' => $items]]);
            return back()->with('success', "\"{$removed}\" berhasil dihapus.");
        }

        return back()->with('error', 'Index tidak valid.');
    }

    /**
     * Update CTA section
     */
    public function updateCta(Request $request)
    {
        $section = HomepageSection::getSection('cta');

        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'email' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
        ]);

        $content = [
            'heading' => $validated['heading'],
            'description' => $validated['description'] ?? '',
            'email' => $validated['email'] ?? '',
            'whatsapp' => $validated['whatsapp'] ?? '',
        ];

        if ($section) {
            $section->update(['content' => $content]);
        } else {
            HomepageSection::create([
                'section_key' => 'cta',
                'title' => 'Call to Action',
                'content' => $content,
                'sort_order' => 7,
            ]);
        }

        return redirect()->route('root.homepage.edit', 'cta')
            ->with('success', 'CTA section berhasil diperbarui.');
    }

    /**
     * Update process/how-it-works section
     */
    public function updateProcess(Request $request)
    {
        $section = HomepageSection::getSection('process');

        $validated = $request->validate([
            'subtitle' => 'nullable|string|max:255',
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'steps' => 'required|array|min:1',
            'steps.*.title' => 'required|string|max:255',
            'steps.*.description' => 'required|string|max:500',
        ]);

        $steps = [];
        foreach ($validated['steps'] as $i => $step) {
            $steps[] = [
                'step' => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'title' => $step['title'],
                'description' => $step['description'],
            ];
        }

        $content = [
            'subtitle' => $validated['subtitle'] ?? '',
            'heading' => $validated['heading'],
            'description' => $validated['description'] ?? '',
            'steps' => $steps,
        ];

        if ($section) {
            $section->update(['content' => $content]);
        } else {
            HomepageSection::create([
                'section_key' => 'process',
                'title' => 'Cara Kerja',
                'content' => $content,
                'sort_order' => 6,
            ]);
        }

        return redirect()->route('root.homepage.edit', 'process')
            ->with('success', 'Cara Kerja section berhasil diperbarui.');
    }

    /**
     * Toggle section active status
     */
    public function toggle(string $key)
    {
        $section = HomepageSection::where('section_key', $key)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $section->update(['is_active' => !$section->is_active]);
        $status = $section->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Section \"{$section->title}\" {$status}.");
    }
}
