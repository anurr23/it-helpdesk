<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Load the URL helper to use base_url() and site_url()
		$this->load->helper('url');
		$this->load->helper('form');
        // Enable file caching for auth pages (10 minutes)
        // $this->output->cache(10);
	}

	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			if ($this->session->userdata('role') == 'admin') {
				redirect('admin/beranda');
			} else {
				redirect('buat-tiket');
			}
		}
		// Redirect to login
		redirect('login');
	}

	public function login()
	{
        // Enable Cache for Login page as requested (Cache time in minutes)
        // Note: Caching login pages might cache CSRF tokens, which causes validation failures.
        // It's generally NOT recommended to cache pages with CSRF tokens.
        // I will not cache it here because CSRF is enabled.
        
        if ($this->session->userdata('logged_in')) {
			if ($this->session->userdata('role') == 'admin') {
				redirect('admin/beranda');
			} else {
				redirect('buat-tiket');
			}
		}

		if ($this->input->post()) {
			$username = $this->input->post('username', TRUE);
			$password = $this->input->post('password', TRUE);
            $remember = $this->input->post('remember', TRUE);

			$this->load->model('User_model');
			$user = $this->User_model->login($username, $password);

			if ($user) {
				$session_data = array(
					'user_id'  => $user->id,
					'username' => $user->username,
					'name'     => $user->name,
					'role'     => $user->role,
					'dept'     => isset($user->dept) ? $user->dept : '',
					'approver_id' => isset($user->approver_id) ? $user->approver_id : '',
					'is_atasan'=> isset($user->atasan) ? $user->atasan : 'F',
					'logged_in'=> TRUE
				);
				$this->session->set_userdata($session_data);

				// Redirect based on role
				if ($user->role == 'admin') {
					redirect('admin/beranda');
				} else {
					redirect('buat-tiket');
				}
			} else {
				$this->session->set_flashdata('error', 'Username atau password salah.');
				redirect('login');
			}
		}

		$this->load->view('auth/login');
	}

	public function register()
	{
		if ($this->input->post()) {
			$username = trim($this->input->post('username', TRUE));
			$password = $this->input->post('password', TRUE);
			$password_confirm = $this->input->post('password_confirm', TRUE);
			$email = $this->input->post('email', TRUE);
			$name = $this->input->post('name', TRUE);

			$this->load->model('User_model');

			if ($password !== $password_confirm) {
				$this->session->set_flashdata('error', 'Password dan ulangi password tidak cocok. Silakan periksa kembali.');
				redirect('daftar');
				return;
			}

			// Cek apakah username sudah ada
			if ($this->User_model->check_username_exists($username)) {
				$this->session->set_flashdata('error', 'Username tersebut sudah terdaftar, silakan pilih username lain.');
				$this->session->set_flashdata('error_username', true);
				$this->session->set_flashdata('old_name', $name);
				$this->session->set_flashdata('old_username', $username);
				$this->session->set_flashdata('old_email', $email);
				redirect('daftar');
				return;
			}

			// Data untuk user baru
			$data = array(
				'username' => $username,
				'password' => password_hash($password, PASSWORD_BCRYPT),
				'name'     => $name,
				'email'    => $email,
				'role'     => 'user',
				'atasan'   => 'F'
			);

			if ($this->User_model->insert_user($data)) {
				$this->session->set_flashdata('success', 'Akun berhasil dibuat. Silakan login untuk melanjutkan.');
				redirect('login');
			} else {
				$this->session->set_flashdata('error', 'Gagal membuat akun, silakan coba lagi.');
				redirect('daftar');
			}
		}

		$this->load->view('auth/register');
	}

	public function check_username()
	{
		$username = trim($this->input->get('username', TRUE));

		if ($username === '') {
			return $this->_username_check_response(FALSE, 'Username wajib diisi.');
		}

		if (strlen($username) < 3) {
			return $this->_username_check_response(FALSE, 'Username minimal 3 karakter.');
		}

		$this->load->model('User_model');

		if ($this->User_model->check_username_exists($username)) {
			return $this->_username_check_response(FALSE, 'Username sudah digunakan, silakan pilih yang lain.');
		}

		return $this->_username_check_response(TRUE, 'Username tersedia.');
	}

	private function _username_check_response($available, $message)
	{
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'available' => $available,
				'message'   => $message
			)));
	}

	public function check_username_reset()
	{
		$username = trim($this->input->get('username', TRUE));

		if ($username === '') {
			return $this->_username_reset_response(FALSE, 'Username wajib diisi.', FALSE, FALSE);
		}

		if (strlen($username) < 3) {
			return $this->_username_reset_response(FALSE, 'Username minimal 3 karakter.', FALSE, FALSE);
		}

		$this->load->model('User_model');
		$user = $this->User_model->get_user_by_username($username);

		if (!$user) {
			return $this->_username_reset_response(FALSE, 'Username tidak ditemukan.', FALSE, FALSE);
		}

		$has_email = !empty(trim($user->email ?? ''));
		$has_contact = !empty(trim($user->contact ?? ''));

		if (!$has_email && !$has_contact) {
			return $this->_username_reset_response(
				TRUE,
				'Username ditemukan, namun belum ada kontak yang terdaftar.',
				FALSE,
				FALSE
			);
		}

		$channels = array();
		if ($has_email) $channels[] = 'email';
		if ($has_contact) $channels[] = 'wa';

		return $this->_username_reset_response(
			TRUE,
			'Username ditemukan. Pilih metode pengiriman link reset.',
			$has_email,
			$has_contact,
			$channels
		);
	}

	private function _username_reset_response($found, $message, $has_email, $has_contact, $channels = array())
	{
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'found'       => $found,
				'message'     => $message,
				'has_email'   => (bool) $has_email,
				'has_contact' => (bool) $has_contact,
				'can_reset'   => $has_email || $has_contact,
				'channels'    => $channels
			)));
	}

	public function lupa_password()
	{
		// Buat tabel password_resets jika belum ada
		$this->db->query("CREATE TABLE IF NOT EXISTS password_resets (
			id SERIAL PRIMARY KEY,
			user_id UUID NOT NULL,
			token VARCHAR(255) NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)");

		if ($this->input->post()) {
			$username = trim($this->input->post('username', TRUE));
			$method = $this->input->post('method', TRUE);

			$this->load->model('User_model');
			$user = $this->User_model->get_user_by_username($username);

			if (!$user) {
				$this->session->set_flashdata('error', 'Username tidak ditemukan.');
				redirect('lupa-password');
				return;
			}

			$has_email = !empty(trim($user->email ?? ''));
			$has_contact = !empty(trim($user->contact ?? ''));

			if (!$has_email && !$has_contact) {
				$this->session->set_flashdata('error', 'Akun tidak memiliki email atau nomor HP terdaftar. Silakan hubungi Tim IT.');
				redirect('lupa-password');
				return;
			}

			if ($method === 'email' && !$has_email) {
				$this->session->set_flashdata('error', 'Akun tersebut tidak memiliki alamat email terdaftar.');
				redirect('lupa-password');
				return;
			}

			if ($method === 'wa' && !$has_contact) {
				$this->session->set_flashdata('error', 'Akun tersebut tidak memiliki nomor HP terdaftar.');
				redirect('lupa-password');
				return;
			}

			if (!in_array($method, array('email', 'wa'), TRUE)) {
				$this->session->set_flashdata('error', 'Metode pengiriman tidak valid.');
				redirect('lupa-password');
				return;
			}

			// Generate Token (32 random bytes, hex encoded)
			$token = bin2hex(random_bytes(32));
			
			// Save token
			if ($this->User_model->save_reset_token($user->id, $token)) {
				$reset_link = base_url('reset-password/' . $token);
				$message = "Halo {$user->name},\n\nAnda telah meminta reset password. Silakan klik link berikut untuk membuat password baru:\n\n{$reset_link}\n\nLink ini akan kedaluwarsa dalam 5 menit.\n\nJika Anda tidak merasa meminta reset password, abaikan pesan ini.";

				$success = false;
				if ($method === 'email') {
					if (!empty($user->email)) {
						// Format HTML untuk email
						$html_message = "<div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; background-color: #ffffff;'>
							<h2 style='color: #1e293b; text-align: center;'>Reset Password</h2>
							<p>Halo <b>{$user->name}</b>,</p>
							<p>Seseorang telah meminta untuk melakukan reset password pada akun Anda. Klik tombol di bawah ini untuk mengatur ulang password Anda:</p>
							<div style='text-align: center; margin: 30px 0;'>
								<a href='{$reset_link}' style='background-color: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;'>Atur Ulang Password</a>
							</div>
							<p>Link ini hanya berlaku selama 5 menit ke depan.</p>
							<p style='color: #64748b; font-size: 12px; border-top: 1px solid #e2e8f0; padding-top: 15px;'>Jika Anda tidak merasa meminta reset password, abaikan saja email ini.</p>
						</div>";
						$success = $this->_send_email($user->email, 'Reset Password IT Helpdesk', $html_message);
					} else {
						$this->session->set_flashdata('error', 'Gagal: Akun tersebut tidak memiliki alamat email.');
						redirect('lupa-password');
						return;
					}
				} else if ($method === 'wa') {
					$success = $this->_send_wa($user->contact ?? '', $message);
				}

				if ($success) {
					$channel_label = ($method === 'wa') ? 'WhatsApp' : 'Email';
					$this->session->set_flashdata('success', 'Link reset password berhasil dikirim ke ' . $channel_label . ' Anda. Silakan cek pesan masuk Anda.');
					redirect('lupa-password');
				} else {
					$this->session->set_flashdata('error', 'Gagal mengirim link reset password. Terjadi kesalahan pada sistem pengiriman.');
					redirect('lupa-password');
				}
			} else {
				$this->session->set_flashdata('error', 'Terjadi kesalahan pada server saat membuat token.');
				redirect('lupa-password');
			}
		}

		$this->load->view('auth/forgot_password');
	}

	public function reset_password($token = NULL)
	{
		if (!$token) {
			$this->session->set_flashdata('error', 'Token reset password tidak valid.');
			redirect('lupa-password');
		}

		$this->load->model('User_model');
		$valid_token = $this->User_model->get_valid_reset_token($token);

		if (!$valid_token) {
			$this->session->set_flashdata('error', 'Link reset password sudah tidak berlaku atau salah. Silakan minta link baru.');
			redirect('lupa-password');
		}

		$data['token'] = $token;
		$this->load->view('auth/reset_password', $data);
	}

	public function proses_reset_password()
	{
		$token = $this->input->post('token', TRUE);
		$password = $this->input->post('password', TRUE);
		$password_confirm = $this->input->post('password_confirm', TRUE);

		if ($password !== $password_confirm) {
			$this->session->set_flashdata('error', 'Password tidak sama.');
			redirect('reset-password/' . $token);
			return;
		}

		$this->load->model('User_model');
		$valid_token = $this->User_model->get_valid_reset_token($token);

		if (!$valid_token) {
			$this->session->set_flashdata('error', 'Token tidak valid atau sudah kedaluwarsa.');
			redirect('lupa-password');
			return;
		}

		// Update password
		$update_data = array(
			'password' => password_hash($password, PASSWORD_BCRYPT)
		);
		$this->User_model->update_user($valid_token->user_id, $update_data);

		// Delete the used token
		$this->User_model->delete_reset_token($token);

		$this->session->set_flashdata('success', 'Password Anda berhasil diatur ulang. Silakan login dengan password baru.');
		redirect('login');
	}

	private function _send_email($to, $subject, $message)
    {
        $this->load->library('email');

        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'mail.okalog.co.id',
            'smtp_port' => 465,
            'smtp_user' => 'it02@okalog.co.id',
            'smtp_pass' => 'Oka$1234',
            'smtp_crypto' => 'ssl',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'wordwrap'  => TRUE
        );

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from('it02@okalog.co.id', 'IT Helpdesk System');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        return $this->email->send();
    }

	private function _send_wa($receiver, $msg)
	{
		if (empty($receiver)) return false;

		$db2 = $this->load->database('apps_users_log', TRUE);
		$wa_data = array(
			'msg'         => $msg . "\n\n_*(Mohon untuk tidak membalas pesan ini. Pesan ini di-generate otomatis oleh sistem IT Helpdesk)*_",
			'sender'      => 'IT Helpdesk System',
			'receiver'    => $receiver,
			'transaction' => 'IT HELPDESK'
		);

		return $db2->insert('wa_bot', $wa_data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}

}
