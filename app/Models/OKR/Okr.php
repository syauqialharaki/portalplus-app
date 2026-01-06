<?php

namespace App\Models\OKR;

use App\Models\Admin\Master\Institution;
use App\Models\Admin\Master\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Okr extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'okrs';

    protected $fillable = [
        'institution_id',
        'owner_type',
        'owner_id',
        'period_id',
        'alignment_okr_id',
        'title',
        'description',
        'status',
        'confidence_score',
        'weight',
        'created_by',
    ];

    protected $casts = [
        'confidence_score' => 'float',
        'weight' => 'float',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function alignment()
    {
        return $this->belongsTo(self::class, 'alignment_okr_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'alignment_okr_id');
    }

    public function keyResults()
    {
        return $this->hasMany(KeyResult::class, 'okr_id');
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class, 'okr_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'okr_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
