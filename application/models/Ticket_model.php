<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_ticket_with_user($ticket_id)
    {
        $this->db->select('tickets.*, users.name as user_name, users.email as user_email, users.username as user_username');
        $this->db->from('tickets');
        $this->db->join('users', 'tickets.user_id = users.id');
        $this->db->where('tickets.id', $ticket_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_tickets_by_status($status)
    {
        $this->db->select('tickets.*, users.name as user_name, users.email as user_email, users.username as user_username, atasan.name as atasan_name');
        $this->db->from('tickets');
        $this->db->join('users', 'tickets.user_id = users.id', 'left');
        $this->db->join('users as atasan', 'tickets.atasan_id = atasan.id', 'left');
        if (is_array($status)) {
            $this->db->where_in('tickets.status', $status);
        } else {
            $this->db->where('tickets.status', $status);
        }
        $this->db->order_by('tickets.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_active_tickets()
    {
        $this->db->select('tickets.*, users.name as user_name, users.email as user_email, users.username as user_username, atasan.name as atasan_name, atasan.contact as atasan_contact');
        $this->db->from('tickets');
        $this->db->join('users', 'tickets.user_id = users.id', 'left');
        $this->db->join('users as atasan', 'tickets.atasan_id = atasan.id', 'left');
        $this->db->where('tickets.status !=', 'resolved');
        $this->db->order_by('tickets.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_tickets_history_for_it($it_id)
    {
        $this->db->select('tickets.*, users.name as user_name, users.email as user_email, users.username as user_username, atasan.name as atasan_name');
        $this->db->from('tickets');
        $this->db->join('users', 'tickets.user_id = users.id', 'left');
        $this->db->join('users as atasan', 'tickets.atasan_id = atasan.id', 'left');
        $this->db->where('tickets.status', 'resolved');
        $this->db->where('tickets.resolved_by', $it_id);
        $this->db->order_by('tickets.resolved_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_ticket_stats()
    {
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        $query = $this->db->get('tickets');
        $results = $query->result();

        $stats = [
            'total' => 0,
            'pending_approval' => 0,
            'approved' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'rejected' => 0
        ];

        foreach ($results as $row) {
            if (array_key_exists($row->status, $stats)) {
                $stats[$row->status] = (int)$row->count;
            }
            $stats['total'] += (int)$row->count;
        }

        return $stats;
    }

    public function get_ticket_stats_by_dept()
    {
        $this->db->select('master_dept.dept_name, COUNT(tickets.id) as count');
        $this->db->from('tickets');
        $this->db->join('users', 'tickets.user_id = users.id');
        $this->db->join('master_dept', 'users.dept = master_dept.id', 'left');
        $this->db->group_by('master_dept.dept_name');
        $this->db->order_by('count', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_tickets_by_user($user_id)
    {
        $this->db->select('tickets.*, atasan.name as atasan_name, it_atasan.name as it_atasan_name');
        $this->db->from('tickets');
        $this->db->join('users as atasan', 'tickets.atasan_id = atasan.id', 'left');
        $this->db->join('users as it_atasan', 'tickets.it_atasan_id = CAST(it_atasan.id AS VARCHAR)', 'left');
        $this->db->where('tickets.user_id', $user_id);
        $this->db->order_by('tickets.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_tickets_pending_approval($atasan_id)
    {
        $this->db->select('tickets.*, users.name as user_name, users.email as user_email, users.username as user_username');
        $this->db->from('tickets');
        $this->db->join('users', 'tickets.user_id = users.id', 'left');
        $this->db->where('tickets.atasan_id', $atasan_id);
        $this->db->where('tickets.status', 'pending');
        $this->db->order_by('tickets.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function count_pending_approval($atasan_id)
    {
        $this->db->where('atasan_id', $atasan_id);
        $this->db->where('status', 'pending');
        $this->db->from('tickets');
        return $this->db->count_all_results();
    }
    public function get_tickets_pending_it_approval()
    {
        $this->db->select('tickets.*, users.name as user_name, users.email as user_email, users.username as user_username');
        $this->db->from('tickets');
        $this->db->join('users', 'tickets.user_id = users.id', 'left');
        $this->db->where('tickets.status', 'pending_it');
        $this->db->order_by('tickets.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}
