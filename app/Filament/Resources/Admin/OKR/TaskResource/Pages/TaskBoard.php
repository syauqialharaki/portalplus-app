<?php

namespace App\Filament\Resources\Admin\OKR\TaskResource\Pages;

use App\Filament\Resources\Admin\OKR\TaskResource;
use App\Models\OKR\Task;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class TaskBoard extends Page
{
    protected static string $resource = TaskResource::class;

    protected static string $view = 'filament.resources.admin.okr.task-resource.pages.task-board';

    public array $columns = [];

    public array $tasksByStatus = [];

    public array $priorityColors = [
        'low' => 'emerald',
        'medium' => 'sky',
        'high' => 'amber',
        'critical' => 'rose',
    ];

    public function mount(): void
    {
        $this->columns = TaskResource::statusOptions();
        $this->loadTasks();
    }

    public function loadTasks(): void
    {
        $institutionId = auth()->user()?->institution_id;

        $tasks = Task::query()
            ->with(['okr', 'keyResult'])
            ->when($institutionId, fn ($q) => $q->where('institution_id', $institutionId))
            ->orderByRaw("FIELD(status, 'backlog','todo','in_progress','blocked','done','cancelled')")
            ->orderBy('position')
            ->orderBy('created_at')
            ->get();

        $this->tasksByStatus = $tasks
            ->groupBy('status')
            ->map(fn (Collection $group) => $group
                ->map(function (Task $task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'priority' => $task->priority,
                        'progress' => $task->progress,
                        'due_date' => $task->due_date?->format('d M Y'),
                        'okr_title' => $task->okr?->title,
                    ];
                })
                ->values()
                ->toArray()
            )
            ->toArray();
    }

    public function moveTask(string $taskId, string $newStatus, int $newIndex): void
    {
        $institutionId = auth()->user()?->institution_id;

        // Update the moved task
        Task::query()
            ->when($institutionId, fn ($q) => $q->where('institution_id', $institutionId))
            ->where('id', $taskId)
            ->update([
                'status' => $newStatus,
                'position' => $newIndex,
            ]);

        // Reorder other tasks in the same column
        $tasks = Task::query()
            ->when($institutionId, fn ($q) => $q->where('institution_id', $institutionId))
            ->where('status', $newStatus)
            ->where('id', '!=', $taskId)
            ->orderBy('position')
            ->get();

        $position = 0;
        foreach ($tasks as $task) {
            if ($position === $newIndex) {
                $position++;
            }
            $task->update(['position' => $position]);
            $position++;
        }

        $this->loadTasks();
    }

    public function reorderTasks(array $groups): void
    {
        $institutionId = auth()->user()?->institution_id;

        foreach ($groups as $group) {
            $status = $group['value'] ?? $group['id'] ?? null;
            $items = $group['items'] ?? [];

            if (! $status || empty($items)) {
                continue;
            }

            foreach (array_values($items) as $order => $item) {
                $taskId = $item['value'] ?? $item['id'] ?? null;
                if (! $taskId) {
                    continue;
                }

                Task::query()
                    ->when($institutionId, fn ($q) => $q->where('institution_id', $institutionId))
                    ->where('id', $taskId)
                    ->update([
                        'status' => $status,
                        'position' => $order + 1,
                    ]);
            }
        }

        $this->loadTasks();
    }
}
