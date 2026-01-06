<?php

namespace App\Models\OKR;

use App\Models\Admin\Master\Institution;
use App\Models\Admin\Master\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'check_ins';

    protected $fillable = [
        'institution_id',
        'okr_id',
        'key_result_id',
        'note',
        'current_value',
        'confidence_score',
        'has_blocker',
        'blocker',
        'next_steps',
        'created_by',
    ];

    protected $casts = [
        'current_value' => 'float',
        'confidence_score' => 'float',
        'has_blocker' => 'boolean',
    ];

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
