<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TeKaPeSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // 1. Seed Users
        $defaultPassword = password_hash('password123', PASSWORD_BCRYPT);
        $adminPassword   = password_hash('admin123', PASSWORD_BCRYPT);
        $tentorPassword  = password_hash('tentor123', PASSWORD_BCRYPT);
        $muridPassword   = password_hash('murid123', PASSWORD_BCRYPT);

        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin TeKaPe',
                'username'       => 'admin',
                'password_hash'  => $adminPassword,
                'role'           => 'admin',
                'phone_whatsapp' => '6281234567890',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 2,
                'name'           => 'Tentor Bima Satria, M.Pd.',
                'username'       => 'tentor',
                'password_hash'  => $tentorPassword,
                'role'           => 'tentor',
                'phone_whatsapp' => '6289876543210',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 3,
                'name'           => 'Simon Petrus',
                'username'       => 'simon',
                'password_hash'  => $muridPassword,
                'role'           => 'murid',
                'phone_whatsapp' => '6281311223344',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 4,
                'name'           => 'Budi Santoso',
                'username'       => 'budi',
                'password_hash'  => $muridPassword,
                'role'           => 'murid',
                'phone_whatsapp' => '6281555667788',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id'             => 5,
                'name'           => 'Siti Rahma',
                'username'       => 'siti',
                'password_hash'  => $muridPassword,
                'role'           => 'murid',
                'phone_whatsapp' => '6281999887766',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];

        foreach ($users as $user) {
            $db->table('users')->insert($user);
        }

        // 2. Students Meta
        $studentsMeta = [
            [
                'user_id'         => 3, // Simon
                'is_premium'      => 1,
                'premium_start'   => date('Y-m-d', strtotime('-5 days')),
                'premium_expiry'  => date('Y-m-d', strtotime('+25 days')),
                'attendance_rate' => 92,
            ],
            [
                'user_id'         => 4, // Budi
                'is_premium'      => 0,
                'premium_start'   => null,
                'premium_expiry'  => null,
                'attendance_rate' => 84,
            ],
            [
                'user_id'         => 5, // Siti
                'is_premium'      => 1,
                'premium_start'   => date('Y-m-d', strtotime('-15 days')),
                'premium_expiry'  => date('Y-m-d', strtotime('+15 days')),
                'attendance_rate' => 96,
            ],
        ];
        foreach ($studentsMeta as $meta) {
            $db->table('students_meta')->insert($meta);
        }

        // 3. Settings
        $settings = [
            ['setting_key' => 'website_name', 'setting_value' => 'TeKaPe.id'],
            ['setting_key' => 'website_subtitle', 'setting_value' => 'Tempa Karakteristik & Pengetahuan'],
            ['setting_key' => 'admin_whatsapp', 'setting_value' => '6281234567890'],
            ['setting_key' => 'tentor_whatsapp', 'setting_value' => '6289876543210'],
            ['setting_key' => 'tentor_name', 'setting_value' => 'Tentor Bima Satria, M.Pd.'],
            ['setting_key' => 'secondary_password_hash', 'setting_value' => $adminPassword],
            ['setting_key' => 'premium_price', 'setting_value' => '149000'],
            ['setting_key' => 'premium_duration_days', 'setting_value' => '30'],
            ['setting_key' => 'payment_gateway_name', 'setting_value' => 'Midtrans / QRIS Otomatis'],
            ['setting_key' => 'payment_gateway_status', 'setting_value' => 'active'],
        ];
        foreach ($settings as $setting) {
            $db->table('settings')->insert($setting);
        }

        // 4. Packages
        $packages = [
            [
                'id'            => 1,
                'title'         => 'Simulasi SKD Free Batch 1',
                'description'   => 'Paket latihan soal TWK, TIU, dan TKP pembuka untuk pemetaan kemampuan awal.',
                'type'          => 'free',
                'price'         => 0,
                'duration_days' => 30,
                'status'        => 'active',
                'created_by'    => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 2,
                'title'         => 'Mini Try Out TWK Kebangsaan',
                'description'   => 'Fokus pendalaman materi Nasionalisme, Bela Negara, dan Integritas.',
                'type'          => 'free',
                'price'         => 0,
                'duration_days' => 30,
                'status'        => 'active',
                'created_by'    => 2,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 3,
                'title'         => 'Grand Try Out Kedinasan & CPNS Premium #1',
                'description'   => 'Try Out komprehensif berstandar CAT BKN terbaru dengan sistem passing grade dan pembahasan mendalam.',
                'type'          => 'premium',
                'price'         => 149000,
                'duration_days' => 30,
                'status'        => 'active',
                'created_by'    => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 4,
                'title'         => 'Super Intensif TKP Anti-Gagal',
                'description'   => 'Kumpulan soal TKP berbobot 5 dengan trik analisis pemecahan masalah cepat.',
                'type'          => 'premium',
                'price'         => 99000,
                'duration_days' => 30,
                'status'        => 'active',
                'created_by'    => 2,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];
        foreach ($packages as $pkg) {
            $db->table('packages')->insert($pkg);
        }

        // 5. Categories for Package 1 & 3
        $categories = [
            ['id' => 1, 'package_id' => 1, 'code' => 'TWK', 'name' => 'Tes Wawasan Kebangsaan', 'is_active' => 1, 'max_score' => 100, 'scoring_rule' => '5 per benar, 0 salah'],
            ['id' => 2, 'package_id' => 1, 'code' => 'TIU', 'name' => 'Tes Inteligensia Umum', 'is_active' => 1, 'max_score' => 100, 'scoring_rule' => '5 per benar, 0 salah'],
            ['id' => 3, 'package_id' => 1, 'code' => 'TKP', 'name' => 'Tes Karakteristik Pribadi', 'is_active' => 1, 'max_score' => 100, 'scoring_rule' => 'Skala 1 - 5 per opsi'],
            ['id' => 4, 'package_id' => 3, 'code' => 'TWK', 'name' => 'Tes Wawasan Kebangsaan', 'is_active' => 1, 'max_score' => 150, 'scoring_rule' => '5 per benar, 0 salah'],
            ['id' => 5, 'package_id' => 3, 'code' => 'TIU', 'name' => 'Tes Inteligensia Umum', 'is_active' => 1, 'max_score' => 175, 'scoring_rule' => '5 per benar, 0 salah'],
            ['id' => 6, 'package_id' => 3, 'code' => 'TKP', 'name' => 'Tes Karakteristik Pribadi', 'is_active' => 1, 'max_score' => 225, 'scoring_rule' => 'Skala 1 - 5 per opsi'],
        ];
        foreach ($categories as $cat) {
            $db->table('categories')->insert($cat);
        }

        // 6. Scoring Settings
        $scoringSettings = [
            [
                'package_id'     => 1,
                'formula_type'   => 'average_category',
                'twk_enabled'    => 1,
                'tiu_enabled'    => 1,
                'tkp_enabled'    => 1,
                'twk_score_rule' => '5 per jawaban benar',
                'tiu_score_rule' => '5 per jawaban benar',
                'tkp_score_rule' => 'Skala 1 - 5 per opsi jawaban',
            ],
            [
                'package_id'     => 3,
                'formula_type'   => 'average_category',
                'twk_enabled'    => 1,
                'tiu_enabled'    => 1,
                'tkp_enabled'    => 1,
                'twk_score_rule' => '5 per jawaban benar',
                'tiu_score_rule' => '5 per jawaban benar',
                'tkp_score_rule' => 'Skala 1 - 5 per opsi jawaban',
            ],
        ];
        foreach ($scoringSettings as $ss) {
            $db->table('scoring_settings')->insert($ss);
        }

        // 7. Sample Questions for Package 1
        $questions = [
            // Question 1 (TWK)
            [
                'id'              => 1,
                'package_id'      => 1,
                'category_id'     => 1,
                'question_number' => 1,
                'type'            => 'pilihan_ganda',
                'narrative'       => 'Pancasila sebagai dasar negara memiliki makna bahwa seluruh tatanan hukum dan penyelenggaraan kenegaraan di Republik Indonesia harus berlandaskan nilai-nilai Pancasila. Sikap seorang aparatur negara yang mencerminkan pengamalan sila kedua Pancasila dalam pelayanan publik adalah...',
                'image_url'       => null,
                'expected_answer' => null,
                'discussion'      => 'Sila kedua "Kemanusiaan yang adil dan beradab" menekankan pada persamaan derajat manusia tanpa diskriminasi. Memberikan pelayanan ramah, adil, dan menghargai hak setiap warga tanpa membeda-bedakan latar belakang adalah wujud nyata sila kedua.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            // Question 2 (TIU)
            [
                'id'              => 2,
                'package_id'      => 1,
                'category_id'     => 2,
                'question_number' => 2,
                'type'            => 'pilihan_ganda',
                'narrative'       => 'Semua peserta ujian yang lulus telah mempersiapkan diri dengan giat. Beberapa peserta dari bimbingan TeKaPe.id dinyatakan lulus seleksi kedinasan. Kesimpulan yang paling tepat adalah...',
                'image_url'       => null,
                'expected_answer' => null,
                'discussion'      => 'Berdasarkan premis mayor dan minor: Semua yang lulus giat mempersiapkan diri. Beberapa murid TeKaPe lulus. Maka, beberapa peserta dari bimbingan TeKaPe.id telah mempersiapkan diri dengan giat.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            // Question 3 (TKP)
            [
                'id'              => 3,
                'package_id'      => 1,
                'category_id'     => 3,
                'question_number' => 3,
                'type'            => 'pilihan_ganda',
                'narrative'       => 'Anda bertugas di loket pelayanan masyarakat. Menjelang jam istirahat siang, datang seorang warga lansia yang tampak kebingungan mengurus berkas verifikasi data kependudukan. Sementara rekan kerja Anda sudah beranjak untuk makan siang. Tindakan Anda adalah...',
                'image_url'       => null,
                'expected_answer' => null,
                'discussion'      => 'Aspek Pelayanan Publik & Kepedulian Sosial: Opsi dengan skor 5 adalah tetap melayani dan memandu lansia tersebut sampai selesai dengan tulus, baru kemudian mengambil waktu istirahat secara proporsional.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            // Question 4 (TWK - Isian Singkat)
            [
                'id'              => 4,
                'package_id'      => 1,
                'category_id'     => 1,
                'question_number' => 4,
                'type'            => 'isian',
                'narrative'       => 'Sebutkan nama lembaga tinggi negara yang berwenang memutus sengketa kewenangan lembaga negara yang kewenangannya diberikan oleh Undang-Undang Dasar 1945!',
                'image_url'       => null,
                'expected_answer' => 'Mahkamah Konstitusi',
                'discussion'      => 'Berdasarkan Pasal 24C ayat (1) UUD 1945, Mahkamah Konstitusi (MK) berwenang mengadili pada tingkat pertama dan terakhir yang putusannya bersifat final untuk memutus sengketa kewenangan lembaga negara.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            // Question 5 (TIU - Deret Angka)
            [
                'id'              => 5,
                'package_id'      => 1,
                'category_id'     => 2,
                'question_number' => 5,
                'type'            => 'pilihan_ganda',
                'narrative'       => 'Tentukan angka kelanjutan dari pola deret berikut: 3, 7, 15, 31, 63, ...',
                'image_url'       => null,
                'expected_answer' => null,
                'discussion'      => 'Pola pertambahan: +4, +8, +16, +32, maka selanjutnya +64. Sehingga 63 + 64 = 127. Atau dengan rumus 2x + 1.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        foreach ($questions as $q) {
            $db->table('questions')->insert($q);
        }

        // 8. Options for Questions
        $options = [
            // Options Q1
            ['question_id' => 1, 'option_label' => 'A', 'option_text' => 'Mengutamakan pelayanan kepada orang yang memiliki hubungan kekerabatan terlebih dahulu', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 1, 'option_label' => 'B', 'option_text' => 'Memberikan pelayanan secara ramah, adil, dan tanpa membedakan suku, agama, dan status sosial', 'score' => 5, 'is_correct' => 1],
            ['question_id' => 1, 'option_label' => 'C', 'option_text' => 'Bekerja hanya sesuai perintah pimpinan tanpa inisiatif kemanusiaan', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 1, 'option_label' => 'D', 'option_text' => 'Meminta imbalan sukarela atas bantuan pelayanan tambahan yang diberikan', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 1, 'option_label' => 'E', 'option_text' => 'Menolak melayani masyarakat yang tidak berpakaian rapi', 'score' => 0, 'is_correct' => 0],

            // Options Q2
            ['question_id' => 2, 'option_label' => 'A', 'option_text' => 'Beberapa peserta dari bimbingan TeKaPe.id telah mempersiapkan diri dengan giat', 'score' => 5, 'is_correct' => 1],
            ['question_id' => 2, 'option_label' => 'B', 'option_text' => 'Semua peserta bimbingan TeKaPe.id pasti lulus seleksi', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 2, 'option_label' => 'C', 'option_text' => 'Peserta yang tidak lulus berasal dari luar TeKaPe.id', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 2, 'option_label' => 'D', 'option_text' => 'Persiapan giat hanya dilakukan oleh murid TeKaPe.id', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 2, 'option_label' => 'E', 'option_text' => 'Tidak ada peserta TeKaPe.id yang tidak mempersiapkan diri', 'score' => 0, 'is_correct' => 0],

            // Options Q3 (TKP scale 1 to 5)
            ['question_id' => 3, 'option_label' => 'A', 'option_text' => 'Menyapa dengan sopan, mempersilakan duduk, dan membantu proses verifikasi data sampai tuntas sebelum istirahat', 'score' => 5, 'is_correct' => 1],
            ['question_id' => 3, 'option_label' => 'B', 'option_text' => 'Meminta lansia tersebut menunggu petugas piket pengganti yang akan datang setelah jam makan', 'score' => 3, 'is_correct' => 0],
            ['question_id' => 3, 'option_label' => 'C', 'option_text' => 'Memberikan formulir dan menyuruh beliau membacanya sendiri di ruang tunggu', 'score' => 2, 'is_correct' => 0],
            ['question_id' => 3, 'option_label' => 'D', 'option_text' => 'Mengarahkan warga lansia ke meja satpam agar diarahkan lebih lanjut', 'score' => 4, 'is_correct' => 0],
            ['question_id' => 3, 'option_label' => 'E', 'option_text' => 'Menutup loket tepat waktu karena jam istirahat merupakan hak pegawai yang mutlak', 'score' => 1, 'is_correct' => 0],

            // Options Q5
            ['question_id' => 5, 'option_label' => 'A', 'option_text' => '125', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 5, 'option_label' => 'B', 'option_text' => '126', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 5, 'option_label' => 'C', 'option_text' => '127', 'score' => 5, 'is_correct' => 1],
            ['question_id' => 5, 'option_label' => 'D', 'option_text' => '128', 'score' => 0, 'is_correct' => 0],
            ['question_id' => 5, 'option_label' => 'E', 'option_text' => '130', 'score' => 0, 'is_correct' => 0],
        ];

        foreach ($options as $opt) {
            $db->table('question_options')->insert($opt);
        }

        // 9. Schedules
        $schedules = [
            [
                'id'           => 1,
                'subject'      => 'TWK',
                'day'          => 'Senin',
                'date'         => date('Y-m-d', strtotime('next Monday')),
                'start_time'   => '19:00',
                'end_time'     => '20:00',
                'platform'     => 'Google Meet',
                'meeting_link' => 'https://meet.google.com/tek-ape-twk',
                'status'       => 'active',
            ],
            [
                'id'           => 2,
                'subject'      => 'TIU',
                'day'          => 'Rabu',
                'date'         => date('Y-m-d', strtotime('next Wednesday')),
                'start_time'   => '19:00',
                'end_time'     => '20:00',
                'platform'     => 'Zoom',
                'meeting_link' => 'https://zoom.us/j/8899001122?pwd=TeKaPeTIU',
                'status'       => 'active',
            ],
            [
                'id'           => 3,
                'subject'      => 'TKP',
                'day'          => 'Jumat',
                'date'         => date('Y-m-d', strtotime('next Friday')),
                'start_time'   => '19:00',
                'end_time'     => '20:30',
                'platform'     => 'Google Meet',
                'meeting_link' => 'https://meet.google.com/tek-ape-tkp',
                'status'       => 'active',
            ],
            [
                'id'           => 4,
                'subject'      => 'TWK',
                'day'          => 'Minggu',
                'date'         => date('Y-m-d', strtotime('next Sunday')),
                'start_time'   => '09:00',
                'end_time'     => '11:00',
                'platform'     => 'Zoom',
                'meeting_link' => 'https://zoom.us/j/1234567890?pwd=TeKaPeAkbar',
                'status'       => 'active',
            ],
        ];
        foreach ($schedules as $sc) {
            $db->table('schedules')->insert($sc);
        }

        // 10. Sample Attendance
        $attendances = [
            ['user_id' => 3, 'schedule_id' => 1, 'subject' => 'TWK', 'date' => date('Y-m-d', strtotime('-7 days')), 'status' => 'hadir', 'created_at' => $now],
            ['user_id' => 3, 'schedule_id' => 2, 'subject' => 'TIU', 'date' => date('Y-m-d', strtotime('-5 days')), 'status' => 'hadir', 'created_at' => $now],
            ['user_id' => 3, 'schedule_id' => 3, 'subject' => 'TKP', 'date' => date('Y-m-d', strtotime('-3 days')), 'status' => 'hadir', 'created_at' => $now],
            ['user_id' => 4, 'schedule_id' => 1, 'subject' => 'TWK', 'date' => date('Y-m-d', strtotime('-7 days')), 'status' => 'hadir', 'created_at' => $now],
            ['user_id' => 4, 'schedule_id' => 2, 'subject' => 'TIU', 'date' => date('Y-m-d', strtotime('-5 days')), 'status' => 'tidak_hadir', 'created_at' => $now],
            ['user_id' => 5, 'schedule_id' => 1, 'subject' => 'TWK', 'date' => date('Y-m-d', strtotime('-7 days')), 'status' => 'hadir', 'created_at' => $now],
        ];
        foreach ($attendances as $att) {
            $db->table('attendances')->insert($att);
        }

        // 11. Tryout Session Example (Simon completed Package 1 with score 82.33)
        $tryout = [
            'id'               => 1,
            'user_id'          => 3, // Simon
            'package_id'       => 1,
            'status'           => 'completed',
            'start_time'       => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'end_time'         => date('Y-m-d H:i:s', strtotime('-1 hours')),
            'duration_seconds' => 3600,
            'final_score'      => 82.33,
            'twk_score'        => 80.0,
            'tiu_score'        => 85.0,
            'tkp_score'        => 82.0,
            'correct_count'    => 4,
            'incorrect_count'  => 1,
            'is_manual_edited' => 0,
            'edited_by'        => null,
            'edited_at'        => null,
            'created_at'       => date('Y-m-d H:i:s', strtotime('-1 hours')),
        ];
        $db->table('tryout_sessions')->insert($tryout);
    }
}
