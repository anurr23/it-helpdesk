<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_password_resets extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'SERIAL',
            ),
            'user_id' => array(
                'type' => 'UUID',
            ),
            'token' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('password_resets');
        
        $this->db->query("ALTER TABLE password_resets ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->dbforge->drop_table('password_resets', TRUE);
    }
}
