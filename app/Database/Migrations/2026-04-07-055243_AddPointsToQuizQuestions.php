<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPointsToQuizQuestions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('quiz_questions', [
            'points' => [
                'type'       => 'INT',
                'default'    => 10,
                'null'       => false,
                'after'      => 'correct_answer',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('quiz_questions', 'points');
    }
}