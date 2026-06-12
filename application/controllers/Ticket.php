<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
        $this->load->model('User_model');
        $this->load->model('Ticket_model');
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

    private function _send_email($to, $subject, $message, $attachment = NULL)
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
        
        $this->email->to($to);
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

        if ($attachment) {
            $this->email->attach('./uploads/tickets/' . $attachment);
        }

        return $this->email->send();
    }

    public function create()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Profile check
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        if ($user) {
            if (password_verify($user->username, $user->password) || empty($user->dept)) {
                $this->session->set_flashdata('error', 'Anda harus mengubah password default dan melengkapi Departemen.');
                redirect('akun');
                return;
            }
        }

        $data_view = [];
        $data_view['is_atasan'] = ($user->atasan == 'T');
        $data_view['has_approver'] = !empty($user->approver_id);
        $data_view['pending_approval_count'] = 0;
        if ($data_view['is_atasan']) {
            $data_view['pending_approval_count'] = $this->Ticket_model->count_pending_approval($user_id);
        }

        if ($this->input->post()) {
            if (empty($user->approver_id)) {
                $this->session->set_flashdata('error', 'Anda tidak dapat mengirim pengajuan. Silakan pilih Atasan (Approver) di menu Profil terlebih dahulu.');
                redirect('buat-tiket');
                return;
            }

            $title = $this->input->post('judul', TRUE);
            $description = $this->input->post('deskripsi', TRUE);
            $atasan_id = $this->session->userdata('approver_id');
            $attachment = NULL;

            if (!empty($_FILES['lampiran']['name'])) {
                $config['upload_path']   = './uploads/tickets/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size']      = 10240;
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('lampiran')) {
                    $upload_data = $this->upload->data();
                    $attachment = $upload_data['file_name'];
                    
                    // Kompresi Gambar (Hanya mengurangi size/quality, tanpa mengubah dimensi/layout)
                    $config_compress['image_library'] = 'gd2';
                    $config_compress['source_image'] = './uploads/tickets/' . $attachment;
                    $config_compress['create_thumb'] = FALSE;
                    $config_compress['maintain_ratio'] = TRUE;
                    $config_compress['quality'] = '60%'; // Kompres ke 60%
                    $config_compress['width'] = $upload_data['image_width'];
                    $config_compress['height'] = $upload_data['image_height'];
                    $config_compress['new_image'] = './uploads/tickets/' . $attachment;

                    $this->load->library('image_lib', $config_compress);
                    $this->image_lib->resize();
                    $this->image_lib->clear();
                    
                } else {
                    $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
                    redirect('buat-tiket');
                    return;
                }
            }

            $uuid_query = $this->db->query("SELECT gen_random_uuid() as uuid");
            $ticket_id = $uuid_query->row()->uuid;

            $data = array(
                'id'          => $ticket_id,
                'user_id'     => $this->session->userdata('user_id'),
                'atasan_id'   => $atasan_id,
                'category'    => 'General',
                'title'       => $title,
                'description' => $description,
                'attachment'  => $attachment,
                'status'      => 'pending'
            );

            $this->db->trans_begin();

            if ($this->db->insert('tickets', $data)) {
                $atasan = $this->User_model->get_user_by_id($atasan_id);
                $approval_link = base_url('persetujuan/guest/' . $ticket_id);
                
                $email_content = "
                    <h3 style='margin-top: 0; color: #0f172a; font-size: 20px;'>Halo {$atasan->name},</h3>
                    <p style='margin-bottom: 25px; font-size: 16px;'>Ada pengajuan tiket bantuan IT baru dari <strong style='color: #0f172a;'>{$this->session->userdata('name')}</strong> yang membutuhkan persetujuan Anda.</p>
                    
                    <div style='background-color: #f8fafc; border-left: 4px solid #0d6efd; padding: 20px; border-radius: 6px; margin-bottom: 35px;'>
                        <p style='margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: bold; margin-bottom: 8px; letter-spacing: 0.5px;'>Judul Permintaan:</p>
                        <p style='margin: 0; color: #1e293b; font-size: 16px; font-weight: bold; margin-bottom: 15px;'>" . htmlspecialchars($title) . "</p>
                        <p style='margin: 0; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: bold; margin-bottom: 8px; letter-spacing: 0.5px;'>Deskripsi Kendala:</p>
                        <p style='margin: 0; color: #1e293b; font-size: 16px; line-height: 1.5;'>" . nl2br($description) . "</p>
                    </div>
                    
                    <p style='text-align: center; margin-bottom: 30px; font-size: 15px; color: #475569;'>Silakan klik tombol di bawah ini untuk melihat detail dan memberikan keputusan persetujuan:</p>
                    
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <a href='{$approval_link}' style='display: inline-block; padding: 16px 36px; background-color: #0d6efd; color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3); transition: all 0.3s ease;'>Tinjau Tiket Sekarang</a>
                    </div>
                ";

                if ($this->_send_email($atasan->email, 'IT Helpdesk Approval', $email_content, $attachment)) {
                    $this->db->trans_commit();
                    
                    $wa_msg = "Halo {$atasan->name},\n\nAda pengajuan tiket bantuan IT baru dari *{$this->session->userdata('name')}* yang membutuhkan persetujuan Anda.\n\n*Judul:* {$title}\n*Deskripsi:*\n{$description}\n\nSilakan klik link di bawah ini untuk melihat detail dan memberikan persetujuan:\n{$approval_link}";
                    $this->_send_wa($atasan->contact ?? '', $wa_msg);

                    $this->session->set_flashdata('success', 'Berhasil! Tiket permintaan bantuan Anda sudah terkirim ke atasan.');
                } else {
                    $this->db->trans_rollback();
                    if ($attachment && file_exists('./uploads/tickets/' . $attachment)) unlink('./uploads/tickets/' . $attachment);
                    $this->session->set_flashdata('error', 'Gagal mengirim email notifikasi ke Atasan. Pengajuan dibatalkan. Error: ' . $this->email->print_debugger(array('headers')));
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal menyimpan data tiket. Silakan coba lagi.');
            }
            redirect('buat-tiket');
        }

        $this->load->view('ticket/create', $data_view);
    }

    public function status()
    {
        $this->load->view('ticket/status');
    }

    public function detail($ticket_id = NULL)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        if (!$ticket_id || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $ticket_id)) {
            $this->load->view('ticket/not_found');
            return;
        }

        $ticket = $this->Ticket_model->get_ticket_with_user($ticket_id);
        
        // Prevent unauthorized access, only the creator, atasan, or IT can view
        if (!$ticket) {
            $this->load->view('ticket/not_found');
            return;
        }

        $data_view['ticket'] = $ticket;
        $data_view['show_back_btn'] = true;
        
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        $data_view['is_atasan'] = ($user && $user->atasan == 'T');
        $data_view['pending_approval_count'] = 0;
        if ($data_view['is_atasan']) {
            $data_view['pending_approval_count'] = $this->Ticket_model->count_pending_approval($user_id);
        }
        $this->load->view('ticket/detail_full', $data_view);
    }

    public function history()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        if ($user && (password_verify($user->username, $user->password) || empty($user->dept))) {
            $this->session->set_flashdata('error', 'Anda harus mengubah password default dan melengkapi Departemen.');
            redirect('akun');
            return;
        }

        $data_view['tickets'] = $this->Ticket_model->get_tickets_by_user($user_id);
        $data_view['is_atasan'] = ($user->atasan == 'T');
        $data_view['pending_approval_count'] = 0;
        if ($data_view['is_atasan']) {
            $data_view['pending_approval_count'] = $this->Ticket_model->count_pending_approval($user_id);
        }

        $this->load->view('ticket/history', $data_view);
    }

    public function approval_list()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        if ($user && (password_verify($user->username, $user->password) || empty($user->dept))) {
            $this->session->set_flashdata('error', 'Anda harus mengubah password default dan melengkapi Departemen.');
            redirect('akun');
            return;
        }

        if ($user->atasan !== 'T') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman persetujuan.');
            redirect('buat-tiket');
            return;
        }

        $data_view['tickets'] = $this->Ticket_model->get_tickets_pending_approval($user_id);
        $data_view['is_atasan'] = true;
        $data_view['pending_approval_count'] = count($data_view['tickets']);

        $this->load->view('ticket/approval_list', $data_view);
    }

    // ATASAN USER APPROVAL URL
    public function approve_guest($ticket_id = NULL)
    {
        if (!$ticket_id || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $ticket_id)) {
            $this->load->view('ticket/not_found');
            return;
        }

        $ticket = $this->Ticket_model->get_ticket_with_user($ticket_id);
        if (!$ticket) {
            $this->load->view('ticket/not_found');
            return;
        }

        $data_view['ticket'] = $ticket;
        $data_view['role'] = 'Atasan User';
        $data_view['process_url'] = base_url('persetujuan/guest/proses/' . $ticket_id);

        if ($ticket->status == 'pending') {
            $this->load->view('ticket/approval', $data_view);
        } else {
            $data_view['show_back_btn'] = true;
            $data_view['back_url'] = base_url();
            $data_view['back_text'] = 'Ke Halaman Utama';
            $this->load->view('ticket/status', $data_view);
        }
    }

    // ATASAN USER APPROVAL LOGIC
    public function process_approval($ticket_id)
    {
        $status_action = $this->input->post('status', TRUE); 
        $ticket = $this->Ticket_model->get_ticket_with_user($ticket_id);

        if (!$ticket || $ticket->status !== 'pending') {
            show_error('Tiket tidak ditemukan atau sudah diproses.');
        }

        if ($status_action == 'rejected') {
            $this->db->where('id', $ticket_id);
            $this->db->update('tickets', array(
                'status' => 'rejected',
                'rejected_by' => $ticket->atasan_id,
                'rejected_at' => date('Y-m-d H:i:s')
            ));
            
            // Notify User
            $email_content = "<h3 style='margin-top: 0;'>Halo {$ticket->user_name},</h3><p>Mohon maaf, pengajuan tiket bantuan IT Anda telah <b>DITOLAK</b> oleh Atasan Anda ({$ticket->atasan_name}).</p>";
            $this->_send_email($ticket->user_email, 'Tiket Bantuan IT Ditolak', $email_content);
            $this->_send_wa($ticket->contact ?? '', "Halo {$ticket->user_name},\n\nMohon maaf, tiket bantuan IT Anda telah ditolak oleh Atasan Anda.");

            $this->session->set_flashdata('success', 'Status tiket berhasil diperbarui (Ditolak).');
            redirect('persetujuan/guest/' . $ticket_id);

        } else if ($status_action == 'approved') {
            // Find IT Atasan
            $it_atasans = $this->User_model->get_it_atasans();
            if (empty($it_atasans)) {
                $this->session->set_flashdata('error', 'Gagal memproses persetujuan: Atasan IT tidak ditemukan di sistem.');
                redirect('persetujuan/guest/' . $ticket_id);
                return;
            }

            $this->db->where('id', $ticket_id);
            $this->db->update('tickets', array(
                'status' => 'pending_it',
                'approved_at' => date('Y-m-d H:i:s')
            ));

            // Notify Atasan IT

            $it_atasans = $this->User_model->get_it_atasans();
            if ($it_atasans) {
                $approval_link = base_url('persetujuan/it/' . $ticket->id);
                $email_content = "
                <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; background-color: #ffffff;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h2 style='color: #0f172a; margin-bottom: 5px;'>Persetujuan IT Manager</h2>
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
                
                foreach ($it_atasans as $it_atasan) {
                    if (!empty(trim($it_atasan->email))) {
                        $this->_send_email($it_atasan->email, 'Persetujuan Tiket IT Masuk', $email_content);
                    }
                    if (!empty(trim($it_atasan->contact))) {
                        $wa_msg = "Halo {$it_atasan->name},\n\nTiket bantuan IT dari *{$ticket->user_name}* membutuhkan persetujuan Anda selaku Atasan IT.\n\n*Judul:* {$ticket->title}\n*Deskripsi:*\n{$ticket->description}\n\nSilakan klik link di bawah ini:\n{$approval_link}";
                        $this->_send_wa($it_atasan->contact, $wa_msg);
                    }
                }
            }
            $this->session->set_flashdata('success', 'Tiket disetujui dan diteruskan ke Atasan IT.');
            redirect('persetujuan/guest/' . $ticket_id);
        } else {
            show_error('Invalid action');
        }
    }

    // ATASAN IT APPROVAL URL
    public function approve_it($ticket_id = NULL)
    {
        if (!$ticket_id || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $ticket_id)) {
            $this->load->view('ticket/not_found');
            return;
        }

        $ticket = $this->Ticket_model->get_ticket_with_user($ticket_id);
        if (!$ticket) {
            $this->load->view('ticket/not_found');
            return;
        }

        $data_view['ticket'] = $ticket;
        $data_view['role'] = 'Atasan IT';
        $data_view['process_url'] = base_url('persetujuan/it/proses/' . $ticket_id);

        if ($ticket->status == 'pending_it') {
            $this->load->view('ticket/approval', $data_view);
        } else {
            $data_view['show_back_btn'] = true;
            $data_view['back_url'] = base_url();
            $data_view['back_text'] = 'Ke Halaman Utama';
            $this->load->view('ticket/status', $data_view);
        }
    }

    // ATASAN IT APPROVAL LOGIC
    public function process_it_approval($ticket_id)
    {
        $status_action = $this->input->post('status', TRUE); 
        $ticket = $this->Ticket_model->get_ticket_with_user($ticket_id);

        if (!$ticket || $ticket->status !== 'pending_it') {
            show_error('Tiket tidak ditemukan atau sudah diproses.');
        }

        $it_atasan_id = $this->session->userdata('user_id');
        if (empty($it_atasan_id)) {
            $it_atasans = $this->User_model->get_it_atasans();
            if (!empty($it_atasans)) {
                $it_atasan_id = $it_atasans[0]->id;
            }
        }

        if ($status_action == 'rejected') {
            $this->db->where('id', $ticket_id);
            $this->db->update('tickets', array(
                'status' => 'rejected',
                'rejected_by' => $it_atasan_id,
                'it_atasan_id' => $it_atasan_id,
                'rejected_at' => date('Y-m-d H:i:s')
            ));
            
            // Notify User & Atasan User
            $email_content = "<h3 style='margin-top: 0;'>Halo {$ticket->user_name},</h3><p>Mohon maaf, pengajuan tiket bantuan IT Anda telah <b>DITOLAK</b> oleh Atasan IT.</p>";
            $this->_send_email($ticket->user_email, 'Tiket Bantuan IT Ditolak', $email_content);
            $this->_send_wa($ticket->contact ?? '', "Halo {$ticket->user_name},\n\nMohon maaf, tiket bantuan IT Anda telah ditolak oleh Atasan IT.");

            $this->session->set_flashdata('success', 'Status tiket berhasil diperbarui (Ditolak).');
            redirect('persetujuan/it/' . $ticket_id);

        } else if ($status_action == 'approved') {
            $this->db->where('id', $ticket_id);
            $this->db->update('tickets', array(
                'status' => 'in_progress',
                'it_atasan_id' => $it_atasan_id,
                'it_approved_at' => date('Y-m-d H:i:s')
            ));

            // Blast Email to IT Staff
            $it_staff_list = $this->User_model->get_it_staff();
            $emails = [];
            foreach ($it_staff_list as $staff) {
                if (!empty($staff->email)) $emails[] = $staff->email;
                
                $wa_msg = "Halo Tim IT,\n\nAda tiket bantuan baru (*Sedang Ditangani IT*) yang telah disetujui Atasan IT.\n\n*Dari:* {$ticket->user_name}\n*Judul:* {$ticket->title}\n*Deskripsi:*\n{$ticket->description}\n\nMohon segera ditindaklanjuti lewat Dashboard Admin.";
                $this->_send_wa($staff->contact ?? '', $wa_msg);
            }

            if (count($emails) > 0) {
                $email_content = "
                    <h3 style='margin-top: 0;'>Halo Tim IT,</h3>
                    <p>Ada tiket bantuan IT baru yang telah disetujui Atasan IT dan <b>menunggu penanganan Anda</b>.</p>
                    <p><b>Dari:</b> {$ticket->user_name}</p>
                    <div style='background-color: #f8fafc; border-left: 4px solid #0d6efd; padding: 20px; border-radius: 6px; margin-bottom: 35px;'>
                        <p><b>Judul:</b> " . htmlspecialchars($ticket->title) . "<br>
                        <b>Deskripsi Kendala:</b><br>" . nl2br($ticket->description) . "</p>
                    </div>
                    <p>Silakan login ke Admin Panel untuk menyelesaikan tiket ini.</p>
                ";
                $this->_send_email($emails, 'PEMBERITAHUAN: Tiket IT Baru Masuk', $email_content);
            }

            $this->session->set_flashdata('success', 'Tiket disetujui dan ditugaskan ke Tim IT.');
            redirect('persetujuan/it/' . $ticket_id);
        } else {
            show_error('Invalid action');
        }
    }
    public function monitoring()
    {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);

        if (!$user || $user->atasan !== 'T') {
            show_error('Akses ditolak. Halaman ini hanya untuk Atasan.', 403, 'Akses Ditolak');
        }

        $dept_id = $this->session->userdata('dept');
        if (empty($dept_id)) {
            show_error('Gagal mengambil data departemen.', 400);
        }

        $year_param = $this->input->get('year');
        $available_years = $this->Ticket_model->get_available_years_by_dept($dept_id);
        
        // Default to current year if none is selected and data exists, or 'all' if no data
        $current_year = date('Y');
        $selected_year = $year_param ? $year_param : (in_array($current_year, $available_years) ? $current_year : (count($available_years) > 0 ? $available_years[0] : 'all'));

        $data['title'] = 'Monitoring Tiket Departemen';
        $data['is_atasan'] = true;
        $data['pending_approval_count'] = $this->Ticket_model->count_pending_approval($this->session->userdata('user_id'));
        
        $data['selected_year'] = $selected_year;
        $data['available_years'] = $available_years;
        
        // Get statistics and data
        $data['tickets'] = $this->Ticket_model->get_tickets_by_dept($dept_id, $selected_year);
        $data['monthly_stats'] = $this->Ticket_model->get_ticket_stats_per_month_by_dept($dept_id, $selected_year);
        $data['top_users'] = $this->Ticket_model->get_top_users_by_dept($dept_id, $selected_year, 5);

        $this->load->view('ticket/monitoring', $data);
    }
}
