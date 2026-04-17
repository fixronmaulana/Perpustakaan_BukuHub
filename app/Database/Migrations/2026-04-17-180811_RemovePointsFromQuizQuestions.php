<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemovePointsFromQuizQuestions extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('quiz_questions', 'points');
    }

    public function down()
    {
        //
    }
}
