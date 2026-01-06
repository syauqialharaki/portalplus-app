@php
    $statuses = $columns;
@endphp

<x-filament-panels::page>
    <div class="flex flex-col gap-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold dark:text-white">Board Task</h2>
                <p class="text-sm text-gray-500">Geser kartu untuk memindah status (mirip Trello).</p>
            </div>
            <div class="flex gap-2">
                <x-filament::button tag="a" :href="\App\Filament\Resources\Admin\OKR\TaskResource::getUrl('index')" color="gray" icon="heroicon-o-list-bullet">
                    Tabel
                </x-filament::button>
                <x-filament::button tag="a" :href="\App\Filament\Resources\Admin\OKR\TaskResource::getUrl('create')" icon="heroicon-o-plus">
                    Task Baru
                </x-filament::button>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-6"
            x-data="{
                init() {
                    this.initSortable();
                },
                initSortable() {
                    const columns = this.$el.querySelectorAll('.task-column');
                    columns.forEach(column => {
                        new Sortable(column, {
                            group: 'tasks',
                            animation: 150,
                            ghostClass: 'opacity-50',
                            chosenClass: 'ring-2',
                            dragClass: 'shadow-lg',
                            draggable: '.task-card',
                            onEnd: (evt) => {
                                const taskId = evt.item.dataset.id;
                                const newStatus = evt.to.dataset.status;
                                const newIndex = evt.newIndex;
                                $wire.moveTask(taskId, newStatus, newIndex);
                            }
                        });
                    });
                }
            }">
            @foreach ($statuses as $status => $label)
                <div class="bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-xl p-3 flex flex-col gap-3 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="font-semibold text-sm dark:text-white">{{ $label }}</div>
                        <span class="text-xs text-gray-500">{{ count($tasksByStatus[$status] ?? []) }}</span>
                    </div>

                    <div class="task-column space-y-2 min-h-[120px]" data-status="{{ $status }}">
                        @forelse ($tasksByStatus[$status] ?? [] as $task)
                            <div class="task-card p-3 bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded-lg hover:border-primary-300 transition cursor-grab active:cursor-grabbing select-none"
                                data-id="{{ $task['id'] }}">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2">{{ $task['title'] }}</div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">OKR: {{ $task['okr_title'] ?? '—' }}</div>
                                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mt-2">
                                    <span class="inline-flex items-center gap-1">
                                        @php
                                            $colorMap = ['low' => 'bg-emerald-500', 'medium' => 'bg-sky-500', 'high' => 'bg-amber-500', 'critical' => 'bg-rose-500'];
                                        @endphp
                                        <span class="h-2.5 w-2.5 rounded-full {{ $colorMap[$task['priority']] ?? 'bg-gray-500' }}"></span>
                                        {{ ucfirst(str_replace('_', ' ', $task['priority'])) }}
                                    </span>
                                    <span>{{ $task['progress'] }}%</span>
                                </div>
                                @if (!empty($task['due_date']))
                                    <div class="text-[11px] text-gray-500 mt-1">Due {{ $task['due_date'] }}</div>
                                @endif
                            </div>
                        @empty
                            <div class="empty-placeholder text-xs text-gray-400 border border-dashed rounded p-3">Belum ada task</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</x-filament-panels::page>
