<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_spk_insulation extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'auto_increment' => TRUE
            ],
            'id_data' => [
                'type' => 'INT',
            ],
            'qty_insulation' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP'
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('id_data');
        $this->dbforge->create_table('spk_insulation', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('spk_insulation', TRUE);
    }
}
