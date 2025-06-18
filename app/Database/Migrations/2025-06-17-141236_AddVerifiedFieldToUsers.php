<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVerifiedFieldToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'verified' => ['type' => 'BOOLEAN', 'default' => false, 'after' => 'reset_expires'],
            'verify_token' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'verified'],
            'verify_expires' => ['type' => 'DATETIME', 'null' => true, 'after' => 'verify_token'],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['verified', 'verify_token', 'verify_expires']);
    }

}
