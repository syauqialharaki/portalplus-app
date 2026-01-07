<?php

namespace App\Models\Admin\Master;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Admin\Master\Institution;
use App\Models\Admin\Master\Unit;
use App\Models\Admin\Master\Position;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'institution_id',
        'unit_id',
        'position_id',
        'nip',
        'name',
        'email',
        'role',
        'avatar',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
