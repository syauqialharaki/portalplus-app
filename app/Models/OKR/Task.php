<?php

namespace App\Models\OKR;

use App\Models\Admin\Master\Institution;
use App\Models\Admin\Master\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tasks';

    protected $fillable = [
        'institution_id',
        'okr_id',
        'key_result_id',
        'title',
        'description',
        'assignee_id',
        'due_date',
        'priority',
        'status',
        'position',
        'progress',
        'is_blocked',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_blocked' => 'boolean',
        'progress' => 'integer',
        'position' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Task $task) {
            if ($task->position !== null) {
                return;
            }

            $query = static::query()
                ->where('institution_id', $task->institution_id)
                ->where('status', $task->status ?? 'backlog');

            $task->position = (int) $query->max('position') + 1;
        });
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function okr()
    {
        return $this->belongsTo(Okr::class);
    }

    public function keyResult()
    {
        return $this->belongsTo(KeyResult::class, 'key_result_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
