<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\TaskIntern;
use App\Models\Notification;
use Illuminate\Console\Command;

class AutoCompleteTasks extends Command
{
    protected $signature = 'tasks:auto-complete';

    protected $description = 'Auto-complete tasks that have passed their deadline (at 00:00)';

    public function handle()
    {
        $now = now();

        $tasks = Task::where('status', Task::STATUS_ACTIVE)
            ->whereNotNull('deadline')
            ->where('deadline', '<', $now->copy()->startOfDay())
            ->whereNull('deleted_at')
            ->get();

        $autoCompletedCount = 0;

        foreach ($tasks as $task) {
            $pendingInterns = TaskIntern::where('task_id', $task->id)
                ->where('status', TaskIntern::STATUS_IN_PROGRESS)
                ->get();

            foreach ($pendingInterns as $taskIntern) {
                $taskIntern->update([
                    'status' => TaskIntern::STATUS_COMPLETED,
                    'note' => 'Ditandai selesai otomatis karena telah melewati deadline.',
                    'completed_at' => $task->deadline,
                ]);

                $intern = $taskIntern->intern;
                if ($intern && $intern->user) {
                    Notification::create([
                        'user_id' => $intern->user_id,
                        'type' => 'TASK_COMPLETED',
                        'title' => 'Tugas Selesai Otomatis',
                        'message' => 'Tugas "' . $task->title . '" telah ditandai selesai otomatis karena telah melewati deadline.',
                        'is_read' => false,
                    ]);
                }

                if ($task->company && $task->company->user) {
                    $internName = $intern->name ?? 'Intern';
                    Notification::create([
                        'user_id' => $task->company->user_id,
                        'type' => 'TASK_COMPLETED',
                        'title' => 'Tugas Selesai Otomatis',
                        'message' => $internName . ' menyelesaikan tugas "' . $task->title . '" (otomatis - deadline terlewati).',
                        'is_read' => false,
                    ]);
                }

                $autoCompletedCount++;
            }
        }

        if ($autoCompletedCount > 0) {
            $this->info("Berhasil menandai {$autoCompletedCount} tugas sebagai selesai otomatis.");
        } else {
            $this->info("Tidak ada tugas yang perlu ditandai selesai otomatis.");
        }

        return Command::SUCCESS;
    }
}
