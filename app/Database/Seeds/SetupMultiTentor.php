<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SetupMultiTentor extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Add assigned_tentor_id to students_meta if not exists
        try {
            $db->query("ALTER TABLE students_meta ADD COLUMN assigned_tentor_id INT NULL DEFAULT NULL");
            echo "Added assigned_tentor_id to students_meta\n";
        } catch (\Exception $e) {
            echo "Column assigned_tentor_id might already exist\n";
        }

        // 2. Check if tentor2 exists, if not create
        $user = $db->table('users')->where('username', 'tentor2')->get()->getRowArray();
        $now = date('Y-m-d H:i:s');
        $dewiId = null;

        if (!$user) {
            $db->table('users')->insert([
                'name'           => 'Dewi Sartika, M.Pd (Tentor TIU)',
                'username'       => 'tentor2',
                'password_hash'  => password_hash('tentor123', PASSWORD_BCRYPT),
                'role'           => 'tentor',
                'phone_whatsapp' => '6289123456789',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
            $dewiId = $db->insertID();
            echo "Created Tentor Dewi (ID: $dewiId)\n";
        } else {
            $dewiId = $user['id'];
            echo "Tentor Dewi exists (ID: $dewiId)\n";
        }

        // Also update Bima's display name to reflect TWK specialty
        $db->table('users')->where('id', 2)->update([
            'name' => 'Bima Sakti, S.Pd (Tentor TWK)'
        ]);

        // 3. Update existing packages:
        // Package 1 & 3: created by Bima (ID: 2)
        $db->table('packages')->where('id', 1)->update(['created_by' => 2]);
        $db->table('packages')->where('id', 2)->update(['created_by' => 2]);
        $db->table('packages')->where('id', 3)->update(['created_by' => 2]);

        // 4. Create a dedicated TIU package for Dewi
        $dewiPkg = $db->table('packages')->where('created_by', $dewiId)->get()->getRowArray();
        $dewiPkgId = null;
        if (!$dewiPkg) {
            $db->table('packages')->insert([
                'title'         => 'Paket Sukses TIU Kedinasan: Berhitung Cepat & Silogisme',
                'description'   => 'Drilling soal TIU standar CAT BKN: Deret, Analogi, Silogisme, dan Berhitung Cepat bersama Kak Dewi.',
                'type'          => 'premium',
                'price'         => 0,
                'duration_days' => 30,
                'status'        => 'active',
                'created_by'    => $dewiId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
            $dewiPkgId = $db->insertID();
            echo "Created TIU Package for Dewi (ID: $dewiPkgId)\n";

            // Category for Dewi's package
            $db->table('categories')->insert([
                'package_id'   => $dewiPkgId,
                'code'         => 'TIU',
                'name'         => 'Tes Inteligensia Umum',
                'is_active'    => 1,
                'max_score'    => 175,
                'scoring_rule' => '5 per benar, 0 salah',
            ]);
            $catId = $db->insertID();

            // Scoring settings for Dewi's package
            $db->table('scoring_settings')->insert([
                'package_id'     => $dewiPkgId,
                'formula_type'   => 'average_category',
                'twk_enabled'    => 0,
                'tiu_enabled'    => 1,
                'tkp_enabled'    => 0,
                'twk_score_rule' => '5 per benar',
                'tiu_score_rule' => '5 per benar (Max 175)',
                'tkp_score_rule' => 'Skala 1-5',
            ]);

            // Sample Question 1 for Dewi's package
            $db->table('questions')->insert([
                'package_id'      => $dewiPkgId,
                'category_id'     => $catId,
                'question_number' => 1,
                'type'            => 'pilihan_ganda',
                'narrative'       => 'Tentukan angka berikutnya dari barisan berikut: 3, 7, 15, 31, 63, ...',
                'discussion'      => 'Pola barisan: dikali 2 lalu ditambah 1. (3x2)+1=7, (7x2)+1=15, (15x2)+1=31, (31x2)+1=63, (63x2)+1=127.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
            $q1Id = $db->insertID();

            $options = [
                ['question_id' => $q1Id, 'option_label' => 'A', 'option_text' => '125', 'score' => 0, 'is_correct' => 0],
                ['question_id' => $q1Id, 'option_label' => 'B', 'option_text' => '126', 'score' => 0, 'is_correct' => 0],
                ['question_id' => $q1Id, 'option_label' => 'C', 'option_text' => '127', 'score' => 5, 'is_correct' => 1],
                ['question_id' => $q1Id, 'option_label' => 'D', 'option_text' => '128', 'score' => 0, 'is_correct' => 0],
                ['question_id' => $q1Id, 'option_label' => 'E', 'option_text' => '129', 'score' => 0, 'is_correct' => 0],
            ];
            $db->table('question_options')->insertBatch($options);

            // Sample Question 2 for Dewi's package
            $db->table('questions')->insert([
                'package_id'      => $dewiPkgId,
                'category_id'     => $catId,
                'question_number' => 2,
                'type'            => 'pilihan_ganda',
                'narrative'       => 'Semua taruna kedinasan berbadan sehat. Simon adalah taruna kedinasan. Kesimpulan yang sah adalah:',
                'discussion'      => 'Premis 1: Semua A adalah B. Premis 2: C adalah A. Kesimpulan: C adalah B (Simon berbadan sehat).',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
            $q2Id = $db->insertID();

            $options2 = [
                ['question_id' => $q2Id, 'option_label' => 'A', 'option_text' => 'Simon belum tentu berbadan sehat', 'score' => 0, 'is_correct' => 0],
                ['question_id' => $q2Id, 'option_label' => 'B', 'option_text' => 'Simon berbadan sehat', 'score' => 5, 'is_correct' => 1],
                ['question_id' => $q2Id, 'option_label' => 'C', 'option_text' => 'Semua yang sehat adalah Simon', 'score' => 0, 'is_correct' => 0],
                ['question_id' => $q2Id, 'option_label' => 'D', 'option_text' => 'Simon adalah dokter kedinasan', 'score' => 0, 'is_correct' => 0],
                ['question_id' => $q2Id, 'option_label' => 'E', 'option_text' => 'Tidak dapat disimpulkan', 'score' => 0, 'is_correct' => 0],
            ];
            $db->table('question_options')->insertBatch($options2);
        } else {
            $dewiPkgId = $dewiPkg['id'];
        }

        // 5. Create Schedules for Tentor Dewi
        $dewiSchedule = $db->table('schedules')->where('tentor_id', $dewiId)->get()->getRowArray();
        if (!$dewiSchedule) {
            $db->table('schedules')->insert([
                'subject'      => 'TIU',
                'tentor_id'    => $dewiId,
                'day'          => 'Rabu',
                'date'         => date('Y-m-d', strtotime('next Wednesday')),
                'start_time'   => '19:30',
                'end_time'     => '21:00',
                'platform'     => 'Google Meet',
                'meeting_link' => 'https://meet.google.com/tek-tiu-dewi',
                'status'       => 'active',
            ]);
            echo "Created Schedule for Tentor Dewi\n";
        }

        // Ensure Bima's schedules have tentor_id = 2
        $db->table('schedules')->where('tentor_id IS NULL')->orWhere('tentor_id', 0)->update(['tentor_id' => 2]);

        // 6. Assign students
        // Simon (3) & Budi (4) -> assigned to Bima (2)
        $db->table('students_meta')->where('user_id', 3)->update(['assigned_tentor_id' => 2]);
        $db->table('students_meta')->where('user_id', 4)->update(['assigned_tentor_id' => 2]);
        // Siti (5) -> assigned to Dewi ($dewiId)
        $db->table('students_meta')->where('user_id', 5)->update(['assigned_tentor_id' => $dewiId]);

        // 7. Add a Tryout Session for Siti on Dewi's TIU package if not exists
        $sitiTryout = $db->table('tryout_sessions')->where('user_id', 5)->where('package_id', $dewiPkgId)->get()->getRowArray();
        if (!$sitiTryout && $dewiPkgId) {
            $db->table('tryout_sessions')->insert([
                'user_id'          => 5,
                'package_id'       => $dewiPkgId,
                'status'           => 'completed',
                'start_time'       => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'end_time'         => date('Y-m-d H:i:s'),
                'duration_seconds' => 1800,
                'final_score'      => 95.0,
                'twk_score'        => 0,
                'tiu_score'        => 95.0,
                'tkp_score'        => 0,
                'correct_count'    => 19,
                'incorrect_count'  => 1,
                'is_manual_edited' => 0,
                'created_at'       => $now,
            ]);
            echo "Created Siti's tryout session on Dewi's TIU package\n";
        }

        echo "Seeding completed successfully!\n";
    }
}
