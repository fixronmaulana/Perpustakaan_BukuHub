<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescriptionToBooks extends Migration
{
    public function up()
    {
        $this->forge->addColumn('books', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'isbn',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('books', 'description');
    }
}