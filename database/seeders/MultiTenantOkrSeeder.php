<?php

namespace Database\Seeders;

use App\Models\Admin\Master\Institution;
use App\Models\Admin\Master\User;
use App\Models\OKR\KeyResult;
use App\Models\OKR\Okr;
use App\Models\OKR\Period;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MultiTenantOkrSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Ensure an institution exists
        $institution = Institution::first() ?: Institution::create([
            'name' => 'Institusi Default',
            'short_name' => 'INST',
            'code' => 'INST01',
            'email' => 'info@example.com',
            'is_active' => true,
        ]);

        // 2) Ensure a user exists and is tied to the institution
        $user = User::firstWhere('email', 'okr.admin@example.com');
        if (! $user || strlen($user->id) !== 36) {
            $userId = (string) Str::uuid();
            $email = $user ? 'okr.admin2@example.com' : 'okr.admin@example.com';
            $user = User::create([
                'id' => $userId,
                'institution_id' => $institution->id,
                'name' => 'OKR Admin',
                'email' => $email,
                'nip' => null,
                'password' => 'password', // hashed by cast
            ]);
        }
        if (! $user->institution_id) {
            $user->update(['institution_id' => $institution->id]);
        }

        // 3) Create an active yearly OKR period if none
        $period = Period::first() ?: Period::create([
            'institution_id' => $institution->id,
            'name' => 'OKR ' . now()->year,
            'year' => now()->year,
            'start_date' => now()->startOfYear()->toDateString(),
            'end_date' => now()->endOfYear()->toDateString(),
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        // 4) Create a sample OKR for the user
        $okr = Okr::first() ?: Okr::create([
            'institution_id' => $institution->id,
            'owner_type' => $user->getMorphClass(),
            'owner_id' => $user->id,
            'period_id' => $period->id,
            'title' => 'Tingkatkan kepuasan pegawai',
            'description' => 'OKR contoh untuk memulai',
            'status' => 'active',
            'confidence_score' => 0.7,
            'weight' => 1,
            'created_by' => $user->id,
        ]);

        // 5) Create sample Key Result if none
        if (! $okr->keyResults()->exists()) {
            KeyResult::create([
                'institution_id' => $institution->id,
                'okr_id' => $okr->id,
                'title' => 'NPS pegawai naik ke 60',
                'metric_type' => 'number',
                'target' => 60,
                'current' => 0,
                'unit' => 'NPS',
                'weight' => 1,
                'status' => 'on_track',
                'confidence_score' => 0.7,
                'due_date' => now()->endOfYear()->toDateString(),
                'created_by' => $user->id,
            ]);
        }
    }
}
