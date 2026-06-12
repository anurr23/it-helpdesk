<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
        $this->load->model('User_model');
        $this->load->model('Department_model');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        
        $data['user'] = $user;
        $data['departments'] = $this->Department_model->get_all_depts();
        // Get atasans excluding self
        $data['atasans'] = $this->User_model->get_all_atasans();
        
        // Cek jika password masih default
        $data['is_default_password'] = password_verify($user->username, $user->password);
        $data['is_profile_incomplete'] = empty($user->dept) || empty($user->approver_id);

        $this->load->view('profile/index', $data);
    }

    public function update()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);

        $is_default_password = password_verify($user->username, $user->password);
        $password = $this->input->post('password', TRUE);
        
        if ($is_default_password && empty($password)) {
            $this->session->set_flashdata('error', 'Anda wajib mengganti password default Anda.');
            redirect('akun');
            return;
        }

        // Validasi agar password baru tidak sama dengan username (default)
        if (!empty($password) && $password === $user->username) {
            $this->session->set_flashdata('error', 'Password baru tidak boleh sama dengan username (password default).');
            redirect('akun');
            return;
        }

        // Validasi password dan ulangi password cocok
        $password_confirm = $this->input->post('password_confirm', TRUE);
        if (!empty($password) && $password !== $password_confirm) {
            $this->session->set_flashdata('error', 'Password baru dan Ulangi Password tidak cocok. Silakan periksa kembali.');
            redirect('akun');
            return;
        }

        $data = array(
            'dept' => $this->input->post('dept', TRUE),
            'approver_id' => $this->input->post('approver_id', TRUE),
            'name' => $this->input->post('name', TRUE),
            'email' => $this->input->post('email', TRUE),
            'contact' => $this->input->post('contact', TRUE),
        );

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if (empty($data['dept'])) {
            $this->session->set_flashdata('error', 'Departemen wajib diisi.');
            redirect('akun');
            return;
        }

        if ($this->User_model->update_user($user_id, $data)) {
            // Update session data
            $this->session->set_userdata('name', $data['name']);
            $this->session->set_userdata('dept', $data['dept']);
            $this->session->set_userdata('approver_id', $data['approver_id']);
            
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
            
            // Redirect based on role
            if ($user->role == 'admin') {
                redirect('admin/tiket');
            } else {
                redirect('buat-tiket');
            }
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profil.');
            redirect('akun');
        }
    }
}
