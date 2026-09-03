<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\InternshipProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = InternshipProgram::with(['company', 'positions'])
            ->whereNull('deleted_at');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $programs = $query->orderByDesc('created_at')->paginate(20);

        return view('dashboard.root.programs.index', compact('programs'));
    }

    public function show(string $slug)
    {
        $program = InternshipProgram::with(['company', 'positions', 'applications.intern', 'applications.position'])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.root.programs.show', compact('program'));
    }

    public function edit(string $slug)
    {
        $program = InternshipProgram::with(['company', 'positions'])
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.root.programs.edit', compact('program'));
    }

    public function update(Request $request, string $slug)
    {
        $program = InternshipProgram::where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'program_start' => 'required|date|after:registration_end',
            'program_end' => 'required|date|after:program_start',
        ], [
            'registration_end.after' => 'Tanggal tutup pendaftaran harus setelah tanggal buka pendaftaran.',
            'program_start.after' => 'Tanggal mulai program harus setelah tanggal tutup pendaftaran.',
            'program_end.after' => 'Tanggal selesai program harus setelah tanggal mulai program.',
        ]);

        $program->update($validated);

        return redirect()->route('root.programs.show', $slug)
            ->with('success', 'Program magang berhasil diperbarui.');
    }

    public function destroy(string $slug)
    {
        $program = InternshipProgram::where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $program->delete();

        return redirect()->route('root.programs.index')
            ->with('success', 'Program magang berhasil dihapus.');
    }
}
