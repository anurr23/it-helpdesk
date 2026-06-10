<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_depts()
    {
        $this->db->select('master_dept.*, COUNT(users.id) as user_count');
        $this->db->from('master_dept');
        $this->db->join('users', 'users.dept = master_dept.id', 'left');
        $this->db->group_by('master_dept.id');
        $this->db->order_by('master_dept.dept_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function update_dept($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('master_dept', $data);
    }

    public function insert_dept($data)
    {
        $uuid_query = $this->db->query("SELECT gen_random_uuid() as uuid");
        $data['id'] = $uuid_query->row()->uuid;
        return $this->db->insert('master_dept', $data);
    }

    public function delete_dept($id)
    {
        $this->db->trans_begin();

        // 1. Set users.dept to NULL for this department
        $this->db->where('dept', $id);
        $this->db->update('users', ['dept' => NULL]);

        // 2. Delete the department
        $this->db->where('id', $id);
        $this->db->delete('master_dept');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }
}
