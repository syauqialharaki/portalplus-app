<?php

namespace Database\Seeders;

use App\Models\Admin\Master\Institution;
use App\Models\Admin\Master\Position;
use App\Models\Admin\Master\Unit;
use App\Models\Admin\Master\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Ensure institution exists
        $institution = Institution::first() ?: Institution::create([
            'name' => 'STT Terpadu Nurul Fikri',
            'short_name' => 'STTNF',
            'code' => 'STTNF01',
            'email' => 'info@nurulfikri.ac.id',
            'is_active' => true,
        ]);

        // 2) Create positions (jabatan)
        $positions = [
            'Kepala' => Position::firstOrCreate(['name' => 'Kepala'], [
                'description' => 'Kepala Unit/Bagian',
                'is_active' => true,
            ]),
            'Staf' => Position::firstOrCreate(['name' => 'Staf'], [
                'description' => 'Staf Operasional',
                'is_active' => true,
            ]),
        ];

        // 3) Create units (satuan kerja)
        $units = [
            'CDC Alumni' => Unit::firstOrCreate(
                ['name' => 'CDC Alumni', 'institution_id' => $institution->id],
                [
                    'description' => 'Career Development Center & Alumni',
                    'is_active' => true,
                ]
            ),
            'Kepegawaian' => Unit::firstOrCreate(
                ['name' => 'Kepegawaian', 'institution_id' => $institution->id],
                [
                    'description' => 'Bagian Kepegawaian',
                    'is_active' => true,
                ]
            ),
        ];

        // 4) Create users
        $usersData = [
            [
                'email' => 'admin@nurulfikri.ac.id',
                'name' => 'Administrator',
                'nip' => '000000001',
                'role' => 'admin',
                'unit_id' => null,
                'position_id' => null,
            ],
            [
                'email' => 'teguh.prasetyo@nurulfikri.ac.id',
                'name' => 'Teguh Prasetyo',
                'nip' => '198501012010011001',
                'role' => 'user',
                'unit_id' => $units['CDC Alumni']->id,
                'position_id' => $positions['Kepala']->id,
            ],
            [
                'email' => 'diah@nurulfikri.ac.id',
                'name' => 'Diah Ayu',
                'nip' => '199003152015012001',
                'role' => 'user',
                'unit_id' => $units['Kepegawaian']->id,
                'position_id' => $positions['Staf']->id,
            ],
        ];

        foreach ($usersData as $userData) {
            $user = User::where('email', $userData['email'])->first();

            if ($user) {
                $user->update([
                    'name' => $userData['name'],
                    'nip' => $userData['nip'],
                    'role' => $userData['role'],
                    'unit_id' => $userData['unit_id'],
                    'position_id' => $userData['position_id'],
                    'institution_id' => $institution->id,
                ]);
            } else {
                User::create([
                    'id' => (string) Str::uuid(),
                    'institution_id' => $institution->id,
                    'unit_id' => $userData['unit_id'],
                    'position_id' => $userData['position_id'],
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'nip' => $userData['nip'],
                    'role' => $userData['role'],
                    'password' => 'password',
                ]);
            }
        }

        $this->command->info('✓ Seeded positions: Kepala, Staf');
        $this->command->info('✓ Seeded units: CDC Alumni, Kepegawaian');
        $this->command->info('✓ Seeded users: admin, teguh.prasetyo, diah');
    }
}
