<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizzesTable extends Migration
{
    public function up()
    {
        // ── quizzes ──────────────────────────────────────────
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'book_id'          => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => false],
            'name'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'description'      => ['type' => 'TEXT', 'null' => true],
            'duration_minutes' => ['type' => 'INT', 'default' => 15, 'null' => false],
            'max_attempts'     => ['type' => 'INT', 'default' => 3, 'null' => false],
            'is_active'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'null' => false],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('book_id', 'books', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quizzes');

        // ── quiz_questions ────────────────────────────────────
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'quiz_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'question'       => ['type' => 'TEXT', 'null' => false],
            'option_a'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => false],
            'option_b'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => false],
            'option_c'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => false],
            'option_d'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => false],
            'correct_answer' => ['type' => 'ENUM', 'constraint' => ['A', 'B', 'C', 'D'], 'null' => false],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('quiz_id', 'quizzes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quiz_questions');

        // ── quiz_attempts — disiapkan untuk iterasi 4 ────────
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'quiz_id'     => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'member_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'score'       => ['type' => 'INT', 'default' => 0, 'null' => false],
            'total'       => ['type' => 'INT', 'default' => 0, 'null' => false],
            'started_at'  => ['type' => 'DATETIME', 'null' => true],
            'finished_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('quiz_id',   'quizzes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('member_id', 'members', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quiz_attempts');
    }

    public function down()
    {
        $this->forge->dropTable('quiz_attempts');
        $this->forge->dropTable('quiz_questions');
        $this->forge->dropTable('quizzes');
    }
}