<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeKaPeTables extends Migration
{
    public function up()
    {
        // 1. Users Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'murid',
            ],
            'phone_whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // 2. Students Meta Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'is_premium' => [
                'type'       => 'INTEGER',
                'constraint' => 1,
                'default'    => 0,
            ],
            'premium_start' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'premium_expiry' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'attendance_rate' => [
                'type'       => 'INTEGER',
                'constraint' => 5,
                'default'    => 92,
            ],
            'assigned_tentor_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('students_meta', true);

        // 3. Packages Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'free', // 'free' or 'premium'
            ],
            'price' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 0,
            ],
            'duration_days' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 30,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active', // 'draft' or 'active'
            ],
            'created_by' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('packages', true);

        // 4. Categories Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'package_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 0,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50, // 'TWK', 'TIU', 'TKP'
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'is_active' => [
                'type'       => 'INTEGER',
                'constraint' => 1,
                'default'    => 1,
            ],
            'max_score' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 100,
            ],
            'scoring_rule' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories', true);

        // 5. Questions Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'package_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'category_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'question_number' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 1,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'pilihan_ganda', // 'pilihan_ganda', 'isian'
            ],
            'narrative' => [
                'type' => 'TEXT',
            ],
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'expected_answer' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'discussion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('questions', true);

        // 6. Question Options Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'question_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'option_label' => [
                'type'       => 'VARCHAR',
                'constraint' => 5, // 'A', 'B', 'C', 'D', 'E'
            ],
            'option_text' => [
                'type' => 'TEXT',
            ],
            'score' => [
                'type'       => 'REAL',
                'default'    => 0,
            ],
            'is_correct' => [
                'type'       => 'INTEGER',
                'constraint' => 1,
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('question_options', true);

        // 7. Scoring Settings Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'package_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'formula_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'average_category',
            ],
            'twk_enabled' => [
                'type'       => 'INTEGER',
                'constraint' => 1,
                'default'    => 1,
            ],
            'tiu_enabled' => [
                'type'       => 'INTEGER',
                'constraint' => 1,
                'default'    => 1,
            ],
            'tkp_enabled' => [
                'type'       => 'INTEGER',
                'constraint' => 1,
                'default'    => 1,
            ],
            'twk_score_rule' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => '5 per benar',
            ],
            'tiu_score_rule' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => '5 per benar',
            ],
            'tkp_score_rule' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'Skala 1-5',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('scoring_settings', true);

        // 8. Tryout Sessions Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'package_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'completed',
            ],
            'start_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'duration_seconds' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 0,
            ],
            'final_score' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
            'twk_score' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
            'tiu_score' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
            'tkp_score' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
            'correct_count' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 0,
            ],
            'incorrect_count' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 0,
            ],
            'is_manual_edited' => [
                'type'       => 'INTEGER',
                'constraint' => 1,
                'default'    => 0,
            ],
            'edited_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'edited_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tryout_sessions', true);

        // 9. Tryout Answers Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'session_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'question_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'user_answer' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_correct' => [
                'type'       => 'INTEGER',
                'constraint' => 1,
                'default'    => 0,
            ],
            'score_earned' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tryout_answers', true);

        // 10. Schedules Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => 50, // 'TWK', 'TIU', 'TKP'
            ],
            'tentor_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'null'       => true,
            ],
            'day' => [
                'type'       => 'VARCHAR',
                'constraint' => 20, // 'Senin', 'Selasa', ...
            ],
            'date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'start_time' => [
                'type'       => 'VARCHAR',
                'constraint' => 10, // '19:00'
            ],
            'end_time' => [
                'type'       => 'VARCHAR',
                'constraint' => 10, // '20:00'
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => 50, // 'Google Meet', 'Zoom'
            ],
            'meeting_link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('schedules', true);

        // 11. Attendances Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'schedule_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'null'       => true,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30, // 'hadir', 'tidak_hadir'
                'default'    => 'hadir',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('attendances', true);

        // 12. Settings Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings', true);

        // 13. Subscriptions Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
            ],
            'order_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'amount' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 149000,
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'QRIS / E-Wallet',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'paid',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expired_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('subscriptions', true);
    }

    public function down()
    {
        $this->forge->dropTable('subscriptions', true);
        $this->forge->dropTable('settings', true);
        $this->forge->dropTable('attendances', true);
        $this->forge->dropTable('schedules', true);
        $this->forge->dropTable('tryout_answers', true);
        $this->forge->dropTable('tryout_sessions', true);
        $this->forge->dropTable('scoring_settings', true);
        $this->forge->dropTable('question_options', true);
        $this->forge->dropTable('questions', true);
        $this->forge->dropTable('categories', true);
        $this->forge->dropTable('packages', true);
        $this->forge->dropTable('students_meta', true);
        $this->forge->dropTable('users', true);
    }
}
