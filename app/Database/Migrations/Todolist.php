<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Todolist extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'list'       => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'status' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default'=>false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('todolist');
    }

    public function down()
    {
        $this->forge->dropTable('todolist');
    }
}