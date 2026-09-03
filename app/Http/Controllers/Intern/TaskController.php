<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskIntern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * List all tasks assigned to this intern
     */
    public function index(Request $request)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            return redirect()->route('intern.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu.');
        }

        $query = Task::with(['program', 'company', 'interns' => function ($q) use ($intern) {
            $q->where('intern_id', $intern->id);
        }])
            ->whereHas('interns', fn($q) => $q->where('intern_id', $intern->id))
            ->whereNull('deleted_at');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderByDesc('created_at')->paginate(20);

        return view('dashboard.intern.tasks.index', compact('tasks'));
    }

    /**
     * Show task detail
     */
    public function show(string $id)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            abort(403, 'Profil intern belum dibuat.');
        }

        $task = Task::with(['program', 'company', 'interns' => function ($q) use ($intern) {
            $q->where('intern_id', $intern->id);
        }])
            ->where('id', $id)
            ->whereHas('interns', fn($q) => $q->where('intern_id', $intern->id))
            ->whereNull('deleted_at')
            ->firstOrFail();

        return view('dashboard.intern.tasks.show', compact('task'));
    }

    /**
     * Accept and start working on task
     * Intern clicks "Ya saya setujui dan mulai mengerjakan"
     */
    public function accept(string $id)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            abort(403, 'Profil intern belum dibuat.');
        }

        $taskIntern = TaskIntern::where('task_id', $id)
            ->where('intern_id', $intern->id)
            ->where('status', TaskIntern::STATUS_PENDING)
            ->firstOrFail();

        $taskIntern->update(['status' => TaskIntern::STATUS_IN_PROGRESS]);

        return back()->with('success', 'Tugas diterima! Selamat mengerjakan.');
    }

    /**
     * Mark task as completed
     * Only available when deadline is approaching (1 week before)
     */
    public function complete(Request $request, string $id)
    {
        $intern = Auth::user()->internProfile;
        if (!$intern) {
            abort(403, 'Profil intern belum dibuat.');
        }

        $validated = $request->validate([
            'note' => 'nullable|string|max:2000',
        ]);

        $taskIntern = TaskIntern::where('task_id', $id)
            ->where('intern_id', $intern->id)
            ->where('status', TaskIntern::STATUS_IN_PROGRESS)
            ->firstOrFail();

        $task = $taskIntern->task;

        // Check if deadline is approaching (1 week before)
        // If no deadline, allow completion anytime
        if ($task->deadline) {
            $oneWeekBefore = now()->diffInDays($task->deadline, false);
            // Allow completion if we're within 1 week of deadline or past it
            // diffInDays returns positive if deadline is in the future
            if ($oneWeekBefore > 7) {
                return back()->with('error', 'Anda baru bisa menyelesaikan tugas ini saat deadline mendekati (1 minggu sebelum deadline).');
            }
        }

        $taskIntern->update([
            'status' => TaskIntern::STATUS_COMPLETED,
            'note' => $validated['note'] ?? null,
            'completed_at' => now(),
        ]);

        // Notify company
        if ($task && $task->company && $task->company->user) {
            \App\Models\Notification::create([
                'user_id' => $task->company->user_id,
                'type' => 'TASK_COMPLETED',
                'title' => 'Tugas Selesai',
                'message' => "{$intern->name} menyelesaikan tugas \"{$task->title}\".",
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Tugas berhasil ditandai selesai!');
    }
}
