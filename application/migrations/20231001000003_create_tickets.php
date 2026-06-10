<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tickets extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'UUID',
            ),
            'user_id' => array(
                'type' => 'UUID',
            ),
            'atasan_id' => array(
                'type' => 'UUID',
                'null' => TRUE,
            ),
            'it_atasan_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'subject' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'description' => array(
                'type' => 'TEXT',
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'pending',
            ),
            'priority' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'category' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ),
            'attachment' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'it_notes' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'resolved_by' => array(
                'type' => 'UUID',
                'null' => TRUE,
            ),
            'resolved_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tickets');
        
        $this->db->query("ALTER TABLE tickets ALTER COLUMN id SET DEFAULT gen_random_uuid()");
        $this->db->query("ALTER TABLE tickets ALTER COLUMN created_at SET DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->dbforge->drop_table('tickets', TRUE);
    }
}
