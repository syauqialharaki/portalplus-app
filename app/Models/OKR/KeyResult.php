<?php

namespace App\Models\OKR;

use App\Models\Admin\Master\Institution;
use App\Models\Admin\Master\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyResult extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'key_results';

    protected $fillable = [
        'institution_id',
        'okr_id',
        'title',
        'metric_type',
        'target',
        'current',
        'unit',
        'weight',
        'status',
        'confidence_score',
        'due_date',
        'created_by',
    ];

    protected $casts = [
        'target' => 'float',
        'current' => 'float',
        'weight' => 'float',
        'confidence_score' => 'float',
        'due_date' => 'date',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function okr()
    {
        return $this->belongsTo(Okr::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'key_result_id');
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class, 'key_result_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
