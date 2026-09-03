<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskIntern;
use App\Models\InternProfile;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * List all tasks for company
     */
    public function index(Request $request)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            return redirect()->route('company.profile.edit')
                ->with('error', 'Lengkapi profil perusahaan terlebih dahulu.');
        }

        $query = Task::with(['program', 'interns.intern'])
            ->where('company_id', $company->id)
            ->whereNull('deleted_at');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderByDesc('created_at')->paginate(20);

        return view('dashboard.company.tasks.index', compact('tasks'));
    }

    /**
     * Show task detail
     */
    public function show(string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $task = Task::with(['program', 'interns.intern.user'])
            ->where('id', $id)
            ->where('company_id', $company->id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.company.tasks.show', compact('task'));
    }

    /**
     * Form to create new task
     */
    public function create()
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $programs = $company->programs()
            ->whereNull('deleted_at')
            ->with(['programInterns.intern'])
            ->get();

        return view('dashboard.company.tasks.create', compact('programs'));
    }

    /**
     * Store new task and assign to interns
     */
    public function store(Request $request)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibut.');
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:internship_programs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'instructions' => 'nullable|string|max:5000',
            'deadline' => 'nullable|date|after:now',
            'priority' => 'required|in:LOW,NORMAL,HIGH,URGENT',
            'is_mandatory' => 'boolean',
            'intern_ids' => 'required|array|min:1',
            'intern_ids.*' => 'exists:intern_profiles,id',
        ]);

        // Ensure program belongs to this company
        $program = $company->programs()
            ->where('id', $validated['program_id'])
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Create task
        $task = Task::create([
            'company_id' => $company->id,
            'program_id' => $validated['program_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'priority' => $validated['priority'],
            'is_mandatory' => $validated['is_mandatory'] ?? true,
            'status' => Task::STATUS_ACTIVE,
        ]);

        $assignedCount = 0;

        // Assign to selected interns
        foreach ($validated['intern_ids'] as $internId) {
            // Verify intern is participant of this program
            $isParticipant = \App\Models\ProgramIntern::where('program_id', $program->id)
                ->where('intern_id', $internId)
                ->whereNull('deleted_at')
                ->exists();

            if (!$isParticipant) {
                continue;
            }

            TaskIntern::create([
                'task_id' => $task->id,
                'intern_id' => $internId,
                'status' => TaskIntern::STATUS_PENDING,
            ]);

            $assignedCount++;

            // Notify intern
            $intern = InternProfile::with('user')->find($internId);
            if ($intern) {
                Notification::create([
                    'user_id' => $intern->user_id,
                    'type' => 'TASK_ASSIGNED',
                    'title' => 'Tugas Baru',
                    'message' => "Anda mendapat tugas \"{$task->title}\" dari {$company->name}.",
                    'is_read' => false,
                ]);
            }
        }

        return redirect()->route('company.tasks.show', $task->id)
            ->with('success', "Tugas berhasil dibuat dan dikirim ke {$assignedCount} intern.");
    }

    /**
     * Close/reopen a task
     */
    public function toggleStatus(string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $task = Task::where('id', $id)
            ->where('company_id', $company->id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $task->update([
            'status' => $task->status === Task::STATUS_ACTIVE ? Task::STATUS_CLOSED : Task::STATUS_ACTIVE,
        ]);

        $statusText = $task->status === Task::STATUS_ACTIVE ? 'dibuka kembali' : 'ditutup';
        return back()->with('success', "Tugas berhasil {$statusText}.");
    }

    /**
     * Delete a task
     */
    public function destroy(string $id)
    {
        $company = Auth::user()->companyProfile;
        if (!$company) {
            abort(404, 'Profil perusahaan belum dibuat.');
        }

        $task = Task::where('id', $id)
            ->where('company_id', $company->id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $task->delete();

        return redirect()->route('company.tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
