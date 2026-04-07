<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tabel_excel extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'sales_order' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'item' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'ordered_quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP'
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tabel_excel', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('tabel_excel', TRUE);
    }
}
