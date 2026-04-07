<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_spk_drawing extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'id_data' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'customer' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'item_code' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'qty_order' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP'
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('id_data');
        $this->dbforge->create_table('spk_drawing', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('spk_drawing', TRUE);
    }
}
