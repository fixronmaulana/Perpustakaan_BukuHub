<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLoanIdToQuizAttempts extends Migration
{
    public function up()
{
    $this->forge->addColumn('quiz_attempts', [
        'loan_id' => [
            'type'       => 'INT',
            'unsigned'   => true,
            'null'       => true,
            'after'      => 'member_id',
        ],
    ]);
    $this->forge->addForeignKey('loan_id', 'loans', 'id', 'SET NULL', 'SET NULL');
    // jalankan manual jika forge tidak support:
    // ALTER TABLE quiz_attempts ADD CONSTRAINT quiz_attempts_loan_id_foreign
    // FOREIGN KEY (loan_id) REFERENCES loans(id) ON DELETE SET NULL ON UPDATE SET NULL;
}

public function down()
{
    $this->forge->dropForeignKey('quiz_attempts', 'quiz_attempts_loan_id_foreign');
    $this->forge->dropColumn('quiz_attempts', 'loan_id');
}
}
