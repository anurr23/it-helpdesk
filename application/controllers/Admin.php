<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
        $this->load->model('User_model');
        $this->load->model('Ticket_model');
        $this->load->model('Department_model');
        
        // Protect page
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Profile check
        $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));
        $is_profile_incomplete = $user && (password_verify($user->username, $user->password) || empty($user->dept) || empty($user->approver_id));

        // Check if they need to complete profile
        $allowed_methods = ['profile'];
        if ($is_profile_incomplete && !in_array($this->router->fetch_method(), $allowed_methods)) {
            redirect('admin/profil');
        }
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

    private function _send_email($to, $subject, $message)
    {
        $this->load->library('email');
        $config['protocol']    = 'smtp';
        $config['smtp_host']   = 'smtp.okalog.co.id';
        $config['smtp_port']   = 587;
        $config['smtp_crypto'] = ''; 
        $config['smtp_user']   = 'it02@okalog.co.id';
        $config['smtp_pass']   = 'Olcoln112233';
        $config['smtp_timeout'] = 30;
        $config['mailtype']    = 'html';
        $config['charset']     = 'utf-8';
        $config['wordwrap']    = TRUE;
        $config['newline']     = "\r\n";
        $config['crlf']        = "\r\n";
        $this->email->initialize($config);

        $this->email->from('it02@okalog.co.id', 'IT Helpdesk System');
        
        if (is_array($to)) {
            $this->email->to(implode(',', $to));
        } else {
            $this->email->to($to);
        }
        $this->email->subject($subject);

        $html_message = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>{$subject}</title>
        </head>
        <body style='margin: 0; padding: 0; background-color: #f0f4f8; font-family: Helvetica, Arial, sans-serif;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0' style='background-color: #f0f4f8; padding: 40px 20px;'>
                <tr>
                    <td align='center'>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0' style='max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05);'>
                            <!-- Header -->
                            <tr>
                                <td align='center' style='background: linear-gradient(135deg, #0d6efd, #003d9b); padding: 40px 20px;'>
                                    <h1 style='color: #ffffff; margin: 0; font-size: 26px; font-weight: bold; letter-spacing: 0.5px;'>IT Helpdesk System</h1>
                                </td>
                            </tr>
                            <!-- Body -->
                            <tr>
                                <td style='padding: 40px 30px; color: #334155; line-height: 1.6;'>
                                    {$message}
                                </td>
                            </tr>
                            <!-- Footer -->
                            <tr>
                                <td align='center' style='background-color: #f1f5f9; padding: 25px; border-top: 1px solid #e2e8f0;'>
                                    <p style='margin: 0; color: #94a3b8; font-size: 13px; line-height: 1.5;'><i><b>(Mohon untuk tidak membalas pesan ini. Pesan ini di-generate otomatis oleh sistem IT Helpdesk)</b></i></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ";

        $this->email->message($html_message);
        return $this->email->send();
    }

    public function index()
    {
        redirect('admin/beranda');
    }

    public function overview()
    {
        $data['title'] = 'Dashboard Utama';
        $data['stats'] = $this->Ticket_model->get_ticket_stats();
        $data['dept_stats'] = $this->Ticket_model->get_ticket_stats_by_dept();
        $this->load->view('admin/overview', $data);
    }

    public function dashboard()
    {
        $data['active_tickets'] = $this->Ticket_model->get_active_tickets();
        $this->load->view('admin/dashboard', $data);
    }

    public function history()
    {
        $data['title'] = 'Riwayat Penyelesaian Tiket';
        $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));
        $data['user'] = $user;
        
        $it_id = $this->session->userdata('user_id');
        
        $it_atasans = $this->User_model->get_it_atasans();
        $is_it_manager = false;
        if ($it_atasans) {
            foreach ($it_atasans as $it) {
                if ($it->id == $it_id) {
                    $is_it_manager = true;
                    break;
                }
            }
        }
        $data['is_it_manager'] = $is_it_manager;
        
        if ($is_it_manager) {
            $data['title'] = 'Semua Riwayat Penyelesaian Tiket (IT Manager)';
            $data['history_tickets'] = $this->Ticket_model->get_all_resolved_tickets();
        } else {
            $data['history_tickets'] = $this->Ticket_model->get_tickets_history_for_it($it_id);
        }
        
        $this->load->view('admin/history', $data);
    }

    public function approval_it()
    {
        $user_id = $this->session->userdata('user_id');
        $it_atasans = $this->User_model->get_it_atasans();
        $is_it_manager = false;
        if ($it_atasans) {
            foreach ($it_atasans as $it) {
                if ($it->id == $user_id) {
                    $is_it_manager = true;
                    break;
                }
            }
        }
        if (!$is_it_manager) {
            $this->session->set_flashdata('error', 'Akses ditolak. Menu ini hanya untuk Atasan IT.');
            redirect('admin/tiket');
        }

        $data['title'] = 'Persetujuan IT';
        $data['pending_tickets'] = $this->Ticket_model->get_tickets_pending_it_approval();
        $this->load->view('admin/approval_it', $data);
    }

    public function users()
    {
        $data['users'] = $this->User_model->get_all_users();
        $data['departments'] = $this->Department_model->get_all_depts();
        $this->load->view('admin/users', $data);
    }

    public function departments()
    {
        $data['departments'] = $this->Department_model->get_all_depts();
        $this->load->view('admin/departments', $data);
    }

    public function resend_approval($id)
    {
        $ticket = $this->Ticket_model->get_ticket_with_user($id);
        if (!$ticket) {
            $this->session->set_flashdata('error', 'Tiket tidak ditemukan.');
            redirect('admin/tiket');
        }

        if ($ticket->status == 'pending') {
            $atasan = $this->User_model->get_user_by_id($ticket->atasan_id);
            if ($atasan) {
                $approval_link = base_url('persetujuan/guest/' . $ticket->id);
                $email_content = "
                <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; background-color: #ffffff;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h2 style='color: #0f172a; margin-bottom: 5px;'>Persetujuan Pengajuan IT (Resend)</h2>
                        <span style='background: #f1f5f9; padding: 5px 15px; border-radius: 20px; color: #475569; font-size: 14px;'>Tiket #IT-" . strtoupper(substr($ticket->id, 0, 6)) . "</span>
                    </div>
                    <div style='background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                        <p style='margin-top: 0;'><b>Dari:</b> {$ticket->user_name} ({$ticket->user_email})</p>
                        <p style='margin-bottom: 0;'><b>Deskripsi:</b><br>" . nl2br(htmlspecialchars($ticket->description)) . "</p>
                    </div>
                    <p style='text-align: center; margin-bottom: 30px; font-size: 15px; color: #475569;'>Silakan klik tombol di bawah ini untuk melihat detail dan memberikan keputusan persetujuan:</p>
                    <div style='text-align: center;'>
                        <a href='{$approval_link}' style='display: inline-block; padding: 12px 25px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;'>Buka Halaman Persetujuan</a>
                    </div>
                </div>";
                
                $wa_msg = "[REMINDER] Halo {$atasan->name},\n\nAda pengajuan tiket bantuan IT dari *{$ticket->user_name}* yang masih MENUNGGU persetujuan Anda.\n\n*Deskripsi:*\n{$ticket->description}\n\nSilakan klik link di bawah ini untuk memberikan persetujuan:\n{$approval_link}";

                $email_sent = false;
                if (!empty($atasan->email)) {
                    $email_sent = $this->_send_email($atasan->email, 'REMINDER: IT Helpdesk Approval', $email_content);
                }
                
                $wa_sent = false;
                if (!empty($atasan->contact)) {
                    $wa_sent = $this->_send_wa($atasan->contact, $wa_msg);
                }
                
                if ($email_sent || $wa_sent) {
                    $this->session->set_flashdata('success', 'Notifikasi persetujuan berhasil dikirim ulang ke Atasan User.');
                } else {
                    $error_info = '';
                    if (!empty($atasan->email)) $error_info .= $this->email->print_debugger(array('headers'));
                    $this->session->set_flashdata('error', 'Gagal mengirim ulang notifikasi. ' . strip_tags($error_info));
                }
            } else {
                $this->session->set_flashdata('error', 'Data Atasan User tidak ditemukan.');
            }
        } elseif ($ticket->status == 'pending_it') {
            $it_atasans = $this->User_model->get_it_atasans();
            if ($it_atasans) {
                $approval_link = base_url('persetujuan/it/' . $ticket->id);
                $email_content = "
                <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; background-color: #ffffff;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h2 style='color: #0f172a; margin-bottom: 5px;'>Persetujuan IT Manager (Resend)</h2>
                        <span style='background: #f1f5f9; padding: 5px 15px; border-radius: 20px; color: #475569; font-size: 14px;'>Tiket #IT-" . strtoupper(substr($ticket->id, 0, 6)) . "</span>
                    </div>
                    <div style='background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                        <p style='margin-top: 0;'><b>Dari:</b> {$ticket->user_name} ({$ticket->user_email})</p>
                        <p style='margin-bottom: 0;'><b>Deskripsi Kendala:</b><br>" . nl2br(htmlspecialchars($ticket->description)) . "</p>
                    </div>
                    <div style='text-align: center;'>
                        <a href='{$approval_link}' style='display: inline-block; padding: 12px 25px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;'>Buka Halaman Persetujuan</a>
                    </div>
                </div>
                ";

                $email_sent = false;
                $wa_sent = false;
                $has_valid_contact = false;
                $error_info = '';

                foreach ($it_atasans as $it_atasan) {
                    $wa_msg = "[REMINDER] Halo {$it_atasan->name},\n\nTiket bantuan IT dari *{$ticket->user_name}* masih MENUNGGU persetujuan Anda selaku Atasan IT.\n\n*Deskripsi:*\n{$ticket->description}\n\nSilakan klik link di bawah ini:\n{$approval_link}";
                    
                    if (!empty(trim($it_atasan->email))) {
                        $has_valid_contact = true;
                        if ($this->_send_email($it_atasan->email, 'REMINDER: Persetujuan Tiket IT Masuk', $email_content)) {
                            $email_sent = true;
                        } else {
                            $error_info .= $this->email->print_debugger(array('headers')) . ' ';
                        }
                    }
                    if (!empty(trim($it_atasan->contact))) {
                        $has_valid_contact = true;
                        if ($this->_send_wa($it_atasan->contact, $wa_msg)) {
                            $wa_sent = true;
                        }
                    }
                }
                
                // DEBUG LOGGING
                file_put_contents('debug_resend.txt', "Blast Resend To " . count($it_atasans) . " IT Managers.\nEmail Sent: " . ($email_sent ? 'true' : 'false') . "\nWA Sent: " . ($wa_sent ? 'true' : 'false') . "\nDebugger: " . $error_info);

                if ($email_sent || $wa_sent) {
                    $this->session->set_flashdata('success', 'Notifikasi persetujuan berhasil diblast ulang ke Atasan IT.');
                } else {
                    if (!$has_valid_contact) {
                        $this->session->set_flashdata('error', 'Gagal mengirim notifikasi: Semua Atasan IT belum mengatur Email maupun Nomor WA di Profil mereka.');
                    } else {
                        $this->session->set_flashdata('error', 'Gagal mengirim ulang notifikasi ke Atasan IT. Server SMTP/WA menolak permintaan. Cek debug_resend.txt untuk log.');
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Data IT Manager tidak ditemukan.');
            }
        } else {
            $this->session->set_flashdata('error', 'Tiket ini tidak sedang dalam tahap persetujuan.');
        }

        redirect('admin/tiket');
    }

    public function resolve_ticket_page($id)
    {
        $ticket = $this->Ticket_model->get_ticket_with_user($id);
        if (!$ticket || $ticket->status != 'in_progress') {
            $this->session->set_flashdata('error', 'Tiket tidak ditemukan atau sudah tidak dalam status In Progress.');
            redirect('admin/tiket');
        }

        $data['ticket'] = $ticket;
        $this->load->view('admin/resolve_ticket', $data);
    }

    public function ticket_detail($id)
    {
        if (!$id || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            show_error('Invalid Ticket ID');
        }

        $ticket = $this->Ticket_model->get_ticket_with_user($id);
        
        if (!$ticket) {
            show_404();
        }

        $data['ticket'] = $ticket;
        $this->load->view('admin/ticket_detail', $data);
    }

    public function profile()
    {
        $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));
        $data['user'] = $user;
        $data['departments'] = $this->Department_model->get_all_depts();
        $data['atasans'] = $this->User_model->get_all_atasans();
        $data['is_default_password'] = password_verify($user->username, $user->password);
        $data['is_profile_incomplete'] = empty($user->dept) || empty($user->approver_id);
        
        $this->load->view('admin/profile', $data);
    }

    public function add_user()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') redirect('admin/beranda');
        
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        
        // Jika password kosong, gunakan username sebagai password default
        if (empty($password)) {
            $password = $username;
        }

        $data = array(
            'username' => $username,
            'name'     => $this->input->post('name', TRUE),
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => $this->input->post('role', TRUE),
            'dept'     => $this->input->post('dept', TRUE),
            'atasan'   => $this->input->post('atasan', TRUE) ? 'T' : 'F',
            'email'    => $this->input->post('email', TRUE),
            'contact'  => $this->input->post('contact', TRUE)
        );

        if ($this->User_model->insert_user($data)) {
            $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan pengguna.');
        }
        redirect('admin/pengguna');
    }

    public function edit_user($id)
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') redirect('admin/beranda');
        
        $data = array(
            'username' => $this->input->post('username', TRUE),
            'name'     => $this->input->post('name', TRUE),
            'role'     => $this->input->post('role', TRUE),
            'dept'     => $this->input->post('dept', TRUE),
            'atasan'   => $this->input->post('atasan', TRUE) ? 'T' : 'F',
            'email'    => $this->input->post('email', TRUE),
            'contact'  => $this->input->post('contact', TRUE)
        );

        $password = $this->input->post('password', TRUE);
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if ($this->User_model->update_user($id, $data)) {
            $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui pengguna.');
        }
        redirect('admin/pengguna');
    }

    public function delete_user($id)
    {
        // Cegah penghapusan diri sendiri
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            redirect('admin/pengguna');
        }

        if ($this->User_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna. (Kemungkinan karena masih memiliki riwayat data)');
        }
        redirect('admin/pengguna');
    }

    public function add_dept()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') redirect('admin/beranda');
        
        $data = array(
            'dept_name' => $this->input->post('dept_name', TRUE)
        );

        if ($this->Department_model->insert_dept($data)) {
            $this->session->set_flashdata('success', 'Departemen berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan departemen.');
        }
        redirect('admin/departemen');
    }

    public function edit_dept($id)
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') redirect('admin/departemen');
        
        $data = array(
            'dept_name' => $this->input->post('dept_name', TRUE)
        );

        if ($this->Department_model->update_dept($id, $data)) {
            $this->session->set_flashdata('success', 'Departemen berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui departemen.');
        }
        redirect('admin/departemen');
    }

    public function delete_dept($id)
    {
        if ($this->Department_model->delete_dept($id)) {
            $this->session->set_flashdata('success', 'Departemen berhasil dihapus. Pengguna yang tergabung dalam departemen tersebut harus memperbarui profilnya.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus departemen.');
        }
        redirect('admin/departemen');
    }

    public function resolve_ticket($ticket_id)
    {
        if (!$ticket_id || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $ticket_id)) {
            show_error('Invalid Ticket ID');
        }

        $ticket = $this->Ticket_model->get_ticket_with_user($ticket_id);
        
        if (!$ticket || $ticket->status !== 'in_progress') {
            $this->session->set_flashdata('error', 'Tiket tidak valid atau tidak dalam status Sedang Ditangani.');
            redirect('admin/beranda');
        }

        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            $this->session->set_flashdata('error', 'Metode tidak diizinkan.');
            redirect('admin/beranda');
        }

        $it_notes = trim($this->input->post('it_notes', TRUE));
        if (empty($it_notes)) {
            $this->session->set_flashdata('error', 'Keterangan perbaikan wajib diisi dan tidak boleh hanya berisi spasi.');
            redirect('admin/tangani-tiket/' . $ticket_id);
        }

        $this->db->where('id', $ticket_id);
        $this->db->update('tickets', array(
            'status' => 'resolved',
            'resolved_by' => $this->session->userdata('user_id'),
            'resolved_at' => date('Y-m-d H:i:s'),
            'it_notes' => $it_notes
        ));

        // Get Atasan User details
        $atasan_user = $this->User_model->get_user_by_id($ticket->atasan_id);
        
        // Get Atasan IT details (Blast to all)
        $it_atasans = $this->User_model->get_it_atasans();

        // Blast WA
        // WA for User
        $wa_msg_user = "PEMBERITAHUAN TIKET SELESAI\n\nHalo {$ticket->user_name},\nTiket bantuan IT yang Anda ajukan telah SELESAI ditangani oleh Tim IT.\n\n*Deskripsi Kendala:*\n{$ticket->description}\n\n*Tindakan / Keterangan IT:*\n{$it_notes}\n\nTerima kasih atas kesabaran Anda.";
        if (!empty($ticket->contact)) $this->_send_wa($ticket->contact, $wa_msg_user);

        // WA for Atasan User
        if ($atasan_user && !empty($atasan_user->contact)) {
            $wa_msg_atasan = "PEMBERITAHUAN TIKET SELESAI\n\nHalo {$atasan_user->name},\nTiket bantuan IT dengan pengaju *{$ticket->user_name}* telah SELESAI ditangani oleh Tim IT.\n\n*Deskripsi Kendala:*\n{$ticket->description}\n\n*Tindakan / Keterangan IT:*\n{$it_notes}\n\nTerima kasih.";
            $this->_send_wa($atasan_user->contact, $wa_msg_atasan);
        }

        // WA for Atasan IT (Blast to all)
        foreach ($it_atasans as $atasan_it) {
            if (!empty($atasan_it->contact)) {
                $wa_msg_it = "PEMBERITAHUAN TIKET SELESAI\n\nHalo {$atasan_it->name},\nTiket bantuan IT dengan pengaju *{$ticket->user_name}* telah SELESAI ditangani oleh Tim IT.\n\n*Deskripsi Kendala:*\n{$ticket->description}\n\n*Tindakan / Keterangan IT:*\n{$it_notes}\n\nTerima kasih.";
                $this->_send_wa($atasan_it->contact, $wa_msg_it);
            }
        }

        // Blast Email
        $email_template = function($name) use ($ticket, $it_notes) {
            return "
                <h3 style='margin-top: 0;'>Pemberitahuan Tiket Selesai</h3>
                <p>Halo <b>{$name}</b>,</p>
                <p>Tiket bantuan IT dari <b>{$ticket->user_name}</b> telah <b>Selesai Ditangani</b> oleh Tim IT.</p>
                <div style='background-color: #f8fafc; border-left: 4px solid #94a3b8; padding: 20px; border-radius: 6px; margin-bottom: 20px;'>
                    <p style='margin-top: 0;'><b>Deskripsi Kendala Awal:</b><br>" . nl2br(htmlspecialchars($ticket->description)) . "</p>
                </div>
                <div style='background-color: #ecfdf5; border-left: 4px solid #10b981; padding: 20px; border-radius: 6px; margin-bottom: 35px;'>
                    <p style='margin-top: 0;'><b>Keterangan / Tindakan Perbaikan:</b><br>" . nl2br(htmlspecialchars($it_notes)) . "</p>
                </div>
                <p>Terima kasih atas kerja sama Anda.</p>
            ";
        };

        if (!empty($ticket->user_email)) {
            $this->_send_email($ticket->user_email, 'PEMBERITAHUAN: Tiket IT Selesai Ditangani', $email_template($ticket->user_name));
        }
        if ($atasan_user && !empty($atasan_user->email) && $atasan_user->email !== $ticket->user_email) {
            $this->_send_email($atasan_user->email, 'PEMBERITAHUAN: Tiket IT Selesai Ditangani', $email_template($atasan_user->name));
        }
        foreach ($it_atasans as $atasan_it) {
            if (!empty($atasan_it->email) && $atasan_it->email !== $ticket->user_email && ($atasan_user ? $atasan_it->email !== $atasan_user->email : true)) {
                $this->_send_email($atasan_it->email, 'PEMBERITAHUAN: Tiket IT Selesai Ditangani', $email_template($atasan_it->name));
            }
        }

        $this->session->set_flashdata('success', 'Tiket berhasil diselesaikan dan notifikasi telah dikirim.');
        redirect('admin/beranda');
    }

    public function edit_user_page($id)
    {
        $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));
        $target_user = $this->User_model->get_user_by_id($id);
        
        if (!$target_user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/pengguna');
        }

        $data['title'] = 'Edit Pengguna';
        $data['user'] = $user;
        $data['target_user'] = $target_user;
        $data['departments'] = $this->Department_model->get_all_depts();
        
        $this->load->view('admin/edit_user', $data);
    }

    public function add_user_page()
    {
        $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));
        
        $data['title'] = 'Tambah Pengguna';
        $data['user'] = $user;
        $data['departments'] = $this->Department_model->get_all_depts();
        
        $this->load->view('admin/add_user', $data);
    }
}
