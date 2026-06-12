<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Tiket</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= base_url('assets/css/material-symbols.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/inter.css') ?>" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }
        body {
            background: linear-gradient(135deg, #ffffff, #dbeafe);
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body class="text-slate-900 min-vh-100 d-flex flex-column antialiased">
    
    <main class="flex-grow-1 d-flex align-items-center justify-content-center px-3 py-5">
        <div class="w-100 d-flex flex-column align-items-center text-center" style="max-width: 448px; gap: 2rem;">
            <?php 
                if ($ticket->status == 'resolved') {
                    $icon = 'check_circle';
                    $icon_color = 'text-success';
                    $bg_color = 'rgba(25, 135, 84, 0.1)';
                    $shadow_color = 'rgba(25, 135, 84, 0.5)';
                    $title = 'Tiket Telah Selesai!';
                    $subtitle = 'Penanganan tiket ini telah diselesaikan oleh Tim IT.';
                } elseif ($ticket->status == 'rejected') {
                    $icon = 'cancel';
                    $icon_color = 'text-danger';
                    $bg_color = 'rgba(220, 53, 69, 0.1)';
                    $shadow_color = 'rgba(220, 53, 69, 0.5)';
                    $title = 'Permintaan Ditolak';
                    $subtitle = 'Tiket pengajuan ini telah ditolak dan tidak akan diproses lebih lanjut.';
                } elseif ($ticket->status == 'pending_it') {
                    $icon = 'hourglass_top';
                    $icon_color = 'text-warning';
                    $bg_color = 'rgba(255, 193, 7, 0.1)';
                    $shadow_color = 'rgba(255, 193, 7, 0.5)';
                    $title = 'Menunggu Persetujuan IT';
                    $subtitle = 'Tiket telah disetujui Atasan dan sedang menunggu persetujuan Atasan IT.';
                } elseif ($ticket->status == 'in_progress') {
                    $icon = 'build';
                    $icon_color = 'text-info';
                    $bg_color = 'rgba(13, 202, 240, 0.1)';
                    $shadow_color = 'rgba(13, 202, 240, 0.5)';
                    $title = 'Sedang Ditangani';
                    $subtitle = 'Tiket sedang dalam proses penanganan oleh Tim IT.';
                } else {
                    $icon = 'pending';
                    $icon_color = 'text-white';
                    $bg_color = 'var(--primary)';
                    $shadow_color = 'rgba(0,82,204,0.5)';
                    $title = 'Menunggu Persetujuan Atasan';
                    $subtitle = 'Terima kasih, permintaan IT Anda telah kami terima dan sedang menunggu persetujuan.';
                }
            ?>
            
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 96px; height: 96px; background: <?= $bg_color ?>; box-shadow: 0 0 30px <?= $shadow_color ?>;">
                <span class="material-symbols-outlined fill <?= $icon_color ?>" style="font-size: 48px;"><?= $icon ?></span>
            </div>
            
            <div class="d-flex flex-column gap-2">
                <h1 class="h3 fw-bold text-primary-custom mb-0"><?= $title ?></h1>
                <p class="text-slate-600 mb-0" style="font-size: 16px;"><?= $subtitle ?></p>
            </div>
            
            <div class="w-100 glass-card p-4 text-start border shadow-sm">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <span class="material-symbols-outlined text-slate-400">info</span>
                    <h2 class="h6 fw-semibold text-slate-900 mb-0">Detail Tiket</h2>
                </div>
                
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-slate-500" style="font-size: 14px;">Ticket ID</span>
                        <span class="text-slate-900 fw-medium" style="font-size: 14px;">#IT-<?= strtoupper(substr($ticket->id, 0, 8)) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-slate-500" style="font-size: 14px;">Judul Permintaan</span>
                        <span class="text-slate-900 fw-medium text-end" style="font-size: 14px; max-width: 60%;"><?= htmlspecialchars($ticket->title) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-slate-500" style="font-size: 14px;">Waktu Pengajuan</span>
                        <span class="text-slate-900 fw-medium" style="font-size: 14px;"><?= date('d M Y, H:i', strtotime($ticket->created_at)) ?></span>
                    </div>
                    <?php if (isset($ticket->approved_at) && $ticket->approved_at): ?>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-slate-500" style="font-size: 14px;">Tgl Approve (Atasan)</span>
                        <span class="text-slate-900 fw-medium" style="font-size: 14px;"><?= date('d M Y, H:i', strtotime($ticket->approved_at)) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($ticket->it_approved_at) && $ticket->it_approved_at): ?>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-slate-500" style="font-size: 14px;">Tgl Approve (IT)</span>
                        <span class="text-slate-900 fw-medium" style="font-size: 14px;"><?= date('d M Y, H:i', strtotime($ticket->it_approved_at)) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($ticket->rejected_at) && $ticket->rejected_at): ?>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-slate-500" style="font-size: 14px;">Waktu Ditolak</span>
                        <span class="text-slate-900 fw-medium" style="font-size: 14px;"><?= date('d M Y, H:i', strtotime($ticket->rejected_at)) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($ticket->resolved_at) && $ticket->resolved_at): ?>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-slate-500" style="font-size: 14px;">Waktu Selesai</span>
                        <span class="text-slate-900 fw-medium" style="font-size: 14px;"><?= date('d M Y, H:i', strtotime($ticket->resolved_at)) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between">
                    <span class="text-slate-500" style="font-size: 14px;">Status Akhir</span>
                    <div class="d-flex align-items-center gap-1 px-2 py-1 rounded-pill" style="background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.6);">
                        <?php 
                            $status_text = '';
                            $status_icon = '';
                            $status_color = '';
                            
                            switch($ticket->status) {
                                case 'pending': $status_text = 'Menunggu Atasan'; $status_icon = 'schedule'; $status_color = 'text-secondary'; break;
                                case 'pending_it': $status_text = 'Menunggu IT'; $status_icon = 'hourglass_top'; $status_color = 'text-warning'; break;
                                case 'in_progress': $status_text = 'Sedang Ditangani'; $status_icon = 'build'; $status_color = 'text-info'; break;
                                case 'resolved': $status_text = 'Selesai'; $status_icon = 'check_circle'; $status_color = 'text-success'; break;
                                case 'rejected': $status_text = 'Ditolak'; $status_icon = 'cancel'; $status_color = 'text-danger'; break;
                            }
                        ?>
                        <span class="material-symbols-outlined <?= $status_color ?>" style="font-size: 16px;">
                            <?= $status_icon ?>
                        </span>
                        <span class="<?= $status_color ?> fw-semibold" style="font-size: 12px;">
                            <?= $status_text ?>
                        </span>
                    </div>
                </div>
            
                <?php if(isset($show_back_btn) && $show_back_btn): ?>
                <div class="mt-4 pt-3 border-top text-center w-100">
                    <a href="<?= isset($back_url) ? $back_url : 'javascript:history.back()' ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-pill px-4">
                        <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span> <?= isset($back_text) ? $back_text : 'Kembali' ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
        </div>
    </main>

</body>
</html>
