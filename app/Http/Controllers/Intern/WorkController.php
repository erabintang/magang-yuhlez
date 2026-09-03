<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkController extends Controller
{
    public function create()
    {
        return view('dashboard.intern.works.create');
    }

    public function store(Request $request)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
            'source_code_url' => 'nullable|url|max:500',
            'deploy_url' => 'nullable|url|max:500',
        ]);

        $slug = $this->generateSlug($validated['title']);

        $work = Work::create([
            'slug' => $slug,
            'title' => $validated['title'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'year' => $validated['year'] ?? null,
            'source_code_url' => $validated['source_code_url'] ?? null,
            'deploy_url' => $validated['deploy_url'] ?? null,
            'work_type' => 'PUBLIC_WORK',
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Auto-attach intern as participant
        \App\Models\WorkIntern::create([
            'work_id' => $work->id,
            'intern_id' => $intern->id,
            'added_at' => now(),
        ]);

        return redirect()->route('intern.works.index')
            ->with('success', 'Karya berhasil dibuat dan dipublikasikan!');
    }

    public function edit(string $slug)
    {
        $intern = Auth::user()->internProfile;
        $work = Work::where('slug', $slug)
            ->whereNull('deleted_at')
            ->whereHas('interns', fn($q) => $q->where('intern_id', $intern->id))
            ->firstOrFail();

        return view('dashboard.intern.works.edit', compact('work'));
    }

    public function update(Request $request, string $slug)
    {
        $intern = Auth::user()->internProfile;
        $work = Work::where('slug', $slug)
            ->whereNull('deleted_at')
            ->whereHas('interns', fn($q) => $q->where('intern_id', $intern->id))
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
            'source_code_url' => 'nullable|url|max:500',
            'deploy_url' => 'nullable|url|max:500',
        ]);

        $work->update($validated);

        return redirect()->route('intern.works.index')
            ->with('success', 'Karya berhasil diperbarui.');
    }

    public function destroy(string $slug)
    {
        $intern = Auth::user()->internProfile;
        $work = Work::where('slug', $slug)
            ->whereNull('deleted_at')
            ->whereHas('interns', fn($q) => $q->where('intern_id', $intern->id))
            ->firstOrFail();

        $work->delete();

        return redirect()->route('intern.works.index')
            ->with('success', 'Karya berhasil dihapus.');
    }

    protected function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        while (Work::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }
}
