<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function login($username, $password)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();
            // Verify password hash
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return FALSE;
    }

    public function get_user_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function get_atasan_by_dept($dept, $current_username)
    {
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->join('master_dept', 'users.dept = master_dept.id');

        if (!empty($dept)) {
            $this->db->where('users.dept', $dept);
        }
        $this->db->where('master_dept.dept_name !=', 'IT');
        $this->db->where('users.atasan', 'T');
        // We solely rely on PHP filtering for the current user to prevent UUID casting errors
        
        $query = $this->db->get();
        $result = $query->result();
        
        // Extra PHP-level filtering to guarantee the current user is removed
        $filtered_result = array();
        foreach ($result as $row) {
            if (strtolower(trim($row->username)) !== strtolower(trim($current_username))) {
                $filtered_result[] = $row;
            }
        }
        
        return $filtered_result;
    }

    public function get_it_atasans()
    {
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->join('master_dept', 'users.dept = master_dept.id');
        $this->db->where('master_dept.dept_name', 'IT');
        $this->db->where('users.atasan', 'T');
        $query = $this->db->get();
        return $query->result(); // Returns all IT Managers
    }

    public function get_it_staff()
    {
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->join('master_dept', 'users.dept = master_dept.id');
        $this->db->where('master_dept.dept_name', 'IT');
        $this->db->where("COALESCE(users.atasan, 'F') !=", 'T');
        $query = $this->db->get();
        return $query->result(); // Multiple staff
    }

    public function get_all_users()
    {
        $this->db->select('users.*, master_dept.dept_name');
        $this->db->from('users');
        $this->db->join('master_dept', 'users.dept = master_dept.id', 'left');
        $this->db->order_by('users.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_user($data)
    {
        $uuid_query = $this->db->query("SELECT gen_random_uuid() as uuid");
        $data['id'] = $uuid_query->row()->uuid;
        return $this->db->insert('users', $data);
    }

    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    public function get_all_atasans()
    {
        $this->db->where('atasan', 'T');
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('users');
        return $query->result();
    }

    public function check_username_exists($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    public function get_user_by_username($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function get_user_by_username_or_email($input)
    {
        $this->db->group_start();
        $this->db->where('username', $input);
        $this->db->or_where('email', $input);
        $this->db->group_end();
        $query = $this->db->get('users');
        return $query->row();
    }

    public function save_reset_token($user_id, $token)
    {
        $data = array(
            'user_id' => $user_id,
            'token' => $token
        );
        return $this->db->insert('password_resets', $data);
    }

    public function get_valid_reset_token($token)
    {
        $this->db->where('token', $token);
        // Expiry 5 minutes: check if created_at is within the last 5 minutes
        $this->db->where('created_at >= NOW() - INTERVAL \'5 MINUTE\'', NULL, FALSE);
        $query = $this->db->get('password_resets');
        return $query->row();
    }

    public function delete_reset_token($token)
    {
        $this->db->where('token', $token);
        return $this->db->delete('password_resets');
    }
}
