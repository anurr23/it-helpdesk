<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_master_dept extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'SERIAL',
            ),
            'dept_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('master_dept');
    }

    public function down()
    {
        $this->dbforge->drop_table('master_dept', TRUE);
    }
}
