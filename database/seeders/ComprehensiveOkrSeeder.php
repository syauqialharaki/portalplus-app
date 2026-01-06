<?php

namespace Database\Seeders;

use App\Models\Admin\Master\Institution;
use App\Models\Admin\Master\User;
use App\Models\OKR\CheckIn;
use App\Models\OKR\KeyResult;
use App\Models\OKR\Okr;
use App\Models\OKR\Period;
use App\Models\OKR\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComprehensiveOkrSeeder extends Seeder
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

        // 2) Create multiple users with UUID
        $users = [];
        $emails = [
            'kepala.akademik@nurulfikri.ac.id',
            'kepala.keuangan@nurulfikri.ac.id',
            'kepala.it@nurulfikri.ac.id',
            'dosen1@nurulfikri.ac.id',
            'dosen2@nurulfikri.ac.id',
        ];

        foreach ($emails as $email) {
            $user = User::firstWhere('email', $email);
            if (!$user || (is_string($user->id) && strlen($user->id) !== 36)) {
                // Delete if exists with non-UUID id
                if ($user) {
                    $user->delete();
                }
                $user = User::create([
                    'id' => (string) Str::uuid(),
                    'institution_id' => $institution->id,
                    'name' => str_replace(['kepala.', '@nurulfikri.ac.id'], ['Kepala ', ''], $email),
                    'email' => $email,
                    'nip' => null,
                    'password' => 'password',
                ]);
            } else {
                if (!$user->institution_id) {
                    $user->update(['institution_id' => $institution->id]);
                }
            }
            $users[$email] = $user;
        }

        // 3) Create OKR Period
        $period = Period::firstWhere('year', now()->year) ?: Period::create([
            'institution_id' => $institution->id,
            'name' => 'OKR ' . now()->year,
            'year' => now()->year,
            'start_date' => now()->startOfYear()->toDateString(),
            'end_date' => now()->endOfYear()->toDateString(),
            'is_active' => true,
            'created_by' => $users[array_key_first($users)]->id,
        ]);

        // 4) Create OKRs and KRs for each user
        $okrSeeds = [
            [
                'email' => 'kepala.akademik@nurulfikri.ac.id',
                'title' => 'Tingkatkan kualitas akademik',
                'description' => 'Meningkatkan standar pembelajaran dan kompetensi lulusan',
                'krs' => [
                    [
                        'title' => 'Rata-rata IPK lulusan naik ke 3.5',
                        'target' => 3.5,
                        'unit' => 'IPK',
                        'metric_type' => 'number',
                    ],
                    [
                        'title' => '90% mahasiswa lulus tepat waktu',
                        'target' => 90,
                        'unit' => '%',
                        'metric_type' => 'percentage',
                    ],
                    [
                        'title' => '80% lulusan terserap kerja dalam 3 bulan',
                        'target' => 80,
                        'unit' => '%',
                        'metric_type' => 'percentage',
                    ],
                ],
            ],
            [
                'email' => 'kepala.keuangan@nurulfikri.ac.id',
                'title' => 'Optimalkan manajemen keuangan',
                'description' => 'Meningkatkan efisiensi dan transparansi keuangan institusi',
                'krs' => [
                    [
                        'title' => 'Pendapatan operasional naik 20%',
                        'target' => 20,
                        'unit' => '%',
                        'metric_type' => 'percentage',
                    ],
                    [
                        'title' => 'Penghematan biaya operasional 15%',
                        'target' => 15,
                        'unit' => '%',
                        'metric_type' => 'percentage',
                    ],
                    [
                        'title' => '100% audit internal tercapai setiap kuartal',
                        'target' => 100,
                        'unit' => '%',
                        'metric_type' => 'percentage',
                    ],
                ],
            ],
            [
                'email' => 'kepala.it@nurulfikri.ac.id',
                'title' => 'Modernisasi infrastruktur IT',
                'description' => 'Meningkatkan keamanan, kecepatan, dan reliabilitas sistem',
                'krs' => [
                    [
                        'title' => 'Uptime sistem mencapai 99.9%',
                        'target' => 99.9,
                        'unit' => '%',
                        'metric_type' => 'percentage',
                    ],
                    [
                        'title' => 'Implementasi 5 modul sistem informasi baru',
                        'target' => 5,
                        'unit' => 'modul',
                        'metric_type' => 'number',
                    ],
                    [
                        'title' => 'Compliance keamanan data 100%',
                        'target' => 100,
                        'unit' => '%',
                        'metric_type' => 'percentage',
                    ],
                ],
            ],
            [
                'email' => 'dosen1@nurulfikri.ac.id',
                'title' => 'Tingkatkan kualitas pengajaran dan penelitian',
                'description' => 'Meningkatkan kompetensi dosen dan kontribusi publikasi',
                'krs' => [
                    [
                        'title' => 'Publikasi 2 artikel jurnal internasional',
                        'target' => 2,
                        'unit' => 'artikel',
                        'metric_type' => 'number',
                    ],
                    [
                        'title' => 'Sertifikasi pelatihan pedagogi untuk 3 topik',
                        'target' => 3,
                        'unit' => 'topik',
                        'metric_type' => 'number',
                    ],
                    [
                        'title' => 'Kepuasan mahasiswa terhadap pengajaran 4.5 bintang',
                        'target' => 4.5,
                        'unit' => 'bintang',
                        'metric_type' => 'number',
                    ],
                ],
            ],
        ];

        $createdOkrs = [];
        foreach ($okrSeeds as $seed) {
            $user = $users[$seed['email']];
            
            $okr = Okr::create([
                'institution_id' => $institution->id,
                'owner_type' => $user::class,
                'owner_id' => $user->id,
                'period_id' => $period->id,
                'title' => $seed['title'],
                'description' => $seed['description'],
                'status' => 'active',
                'confidence_score' => 0.75,
                'weight' => 1,
                'created_by' => $user->id,
            ]);

            $createdOkrs[] = $okr;

            // Create KRs for this OKR
            foreach ($seed['krs'] as $krSeed) {
                $kr = KeyResult::create([
                    'institution_id' => $institution->id,
                    'okr_id' => $okr->id,
                    'title' => $krSeed['title'],
                    'metric_type' => $krSeed['metric_type'],
                    'target' => $krSeed['target'],
                    'current' => rand(0, (int)$krSeed['target'] * 0.8),
                    'unit' => $krSeed['unit'],
                    'weight' => 1,
                    'status' => ['on_track', 'at_risk', 'off_track'][rand(0, 2)],
                    'confidence_score' => 0.7 + (rand(0, 30) / 100),
                    'due_date' => now()->endOfYear()->toDateString(),
                    'created_by' => $user->id,
                ]);

                // Create check-ins untuk KR
                for ($w = 1; $w <= 3; $w++) {
                    CheckIn::create([
                        'institution_id' => $institution->id,
                        'key_result_id' => $kr->id,
                        'okr_id' => $okr->id,
                        'note' => "Progress check-in minggu ke-{$w}",
                        'current_value' => $kr->current + rand(-5, 10),
                        'confidence_score' => 0.6 + (rand(0, 40) / 100),
                        'has_blocker' => rand(0, 1) === 1,
                        'blocker' => rand(0, 1) === 1 ? 'Terkendala resource' : null,
                        'next_steps' => 'Lanjutkan eksekusi sesuai timeline',
                        'created_by' => $user->id,
                    ]);
                }
            }
        }

        // 5) Create Tasks for KRs
        $taskTitles = [
            'Rancang kurikulum baru',
            'Revisi standar evaluasi',
            'Training dosen baru',
            'Audit laporan keuangan',
            'Review anggaran tahunan',
            'Setup monitoring sistem',
            'Implementasi backup otomatis',
            'Dokumentasi API',
            'Develop modul e-learning',
            'Riset tren teknologi',
        ];

        foreach ($createdOkrs as $okr) {
            foreach ($okr->keyResults as $kr) {
                $taskCount = rand(2, 4);
                for ($i = 0; $i < $taskCount; $i++) {
                    Task::create([
                        'institution_id' => $institution->id,
                        'okr_id' => $okr->id,
                        'key_result_id' => $kr->id,
                        'title' => $taskTitles[array_rand($taskTitles)],
                        'description' => 'Task untuk mendukung key result',
                        'assignee_id' => collect($users)->random()->id,
                        'due_date' => now()->addDays(rand(7, 60))->toDateString(),
                        'priority' => ['low', 'medium', 'high', 'critical'][rand(0, 3)],
                        'status' => ['todo', 'in_progress', 'done'][rand(0, 2)],
                        'progress' => rand(0, 100),
                        'is_blocked' => rand(0, 100) > 80,
                        'created_by' => $okr->creator_id,
                    ]);
                }
            }
        }

        $this->command->info('✓ Seeded ' . count($users) . ' users');
        $this->command->info('✓ Seeded ' . count($createdOkrs) . ' OKRs');
        $krCount = 0;
        foreach ($createdOkrs as $okr) {
            $krCount += $okr->keyResults()->count();
        }
        $this->command->info('✓ Seeded ' . $krCount . ' Key Results');
        $this->command->info('✓ Seeded multiple Check-ins dan Tasks');
    }
}
