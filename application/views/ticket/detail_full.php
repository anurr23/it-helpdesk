<?php $this->load->view('ticket/layout/header'); ?>

<div class="glass-card-container mx-auto pb-5" style="max-width: 1100px;">
    
    <!-- Title Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="javascript:history.back()" class="btn btn-light d-inline-flex align-items-center justify-content-center shadow-sm rounded-circle" style="width: 40px; height: 40px;">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="h4 fw-bold text-slate-800 mb-0">Rincian Tiket</h2>
                <span class="text-slate-500 font-monospace" style="font-size: 14px;">#IT-<?= strtoupper(substr($ticket->id, 0, 6)) ?></span>
            </div>
        </div>
        
        <!-- Status Badge Header -->
        <?php 
            $badge_class = '';
            $status_text = '';
            $status_icon = '';
            
            switch($ticket->status) {
                case 'pending': 
                    $badge_class = 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25'; 
                    $status_text = 'Menunggu Atasan';
                    $status_icon = 'schedule';
                    break;
                case 'pending_it': 
                    $badge_class = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25'; 
                    $status_text = 'Menunggu IT';
                    $status_icon = 'hourglass_top';
                    break;
                case 'in_progress': 
                    $badge_class = 'bg-info bg-opacity-10 text-info border border-info border-opacity-25'; 
                    $status_text = 'Sedang Ditangani';
                    $status_icon = 'build';
                    break;
                case 'resolved': 
                    $badge_class = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'; 
                    $status_text = 'Selesai';
                    $status_icon = 'check_circle';
                    break;
                case 'rejected': 
                    $badge_class = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'; 
                    $status_text = 'Ditolak';
                    $status_icon = 'cancel';
                    break;
            }
        ?>
        <span class="badge rounded-pill <?= $badge_class ?> d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm" style="font-size: 14px; font-weight: 600;">
            <span class="material-symbols-outlined" style="font-size: 18px;"><?= $status_icon ?></span>
            <?= $status_text ?>
        </span>
    </div>

    <div class="row g-4">
        <!-- Sidebar Column (Info & Lampiran) -->
        <div class="col-lg-4">
            <div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm h-100">
                <div class="px-4 py-3 border-bottom d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
                    <span class="material-symbols-outlined text-info">info</span>
                    <h5 class="mb-0 fw-bold text-slate-800" style="font-size: 16px;">Informasi Tiket</h5>
                </div>
                <div class="p-4 d-flex flex-column gap-4 flex-grow-1">
                    
                    <!-- Applicant Info -->
                    <div>
                        <p class="text-slate-400 fw-semibold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 1px;">Pengaju</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center shadow-sm" style="width: 48px; height: 48px; font-size: 16px; font-weight: bold;">
                                <?= strtoupper(substr($ticket->user_name, 0, 2)) ?>
                            </div>
                            <div>
                                <div class="fw-bold text-slate-900" style="font-size: 16px;"><?= htmlspecialchars($ticket->user_name) ?></div>
                                <div class="text-slate-500" style="font-size: 13px;"><?= htmlspecialchars($ticket->user_email) ?></div>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color: rgba(0,0,0,0.1); margin: 0;">

                    <!-- Timeline Info -->
                    <div class="d-flex flex-column gap-3">
                        <p class="text-slate-400 fw-semibold text-uppercase mb-0" style="font-size: 11px; letter-spacing: 1px;">Linimasa</p>
                        
                        <div class="d-flex align-items-center gap-3 text-slate-800">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <span class="material-symbols-outlined text-slate-500" style="font-size: 16px;">add_box</span>
                            </div>
                            <div>
                                <div class="fw-medium text-slate-900" style="font-size: 13px;">Dibuat</div>
                                <div class="text-slate-500" style="font-size: 12px;"><?= date('d M Y - H:i', strtotime($ticket->created_at)) ?></div>
                            </div>
                        </div>
                        
                        <?php if (isset($ticket->approved_at) && $ticket->approved_at): ?>
                        <div class="d-flex align-items-center gap-3 text-slate-800">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <span class="material-symbols-outlined text-secondary" style="font-size: 16px;">how_to_reg</span>
                            </div>
                            <div>
                                <div class="fw-medium text-slate-900" style="font-size: 13px;">Disetujui Atasan <?php if(!empty($ticket->atasan_name)): ?><span class="fw-bold" style="color: var(--primary);">(<?= htmlspecialchars($ticket->atasan_name) ?>)</span><?php endif; ?></div>
                                <div class="text-slate-500" style="font-size: 12px;"><?= date('d M Y - H:i', strtotime($ticket->approved_at)) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($ticket->it_approved_at) && $ticket->it_approved_at): ?>
                        <div class="d-flex align-items-center gap-3 text-slate-800">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <span class="material-symbols-outlined text-warning" style="font-size: 16px;">verified</span>
                            </div>
                            <div>
                                <div class="fw-medium text-slate-900" style="font-size: 13px;">Diterima IT <?php if(!empty($ticket->it_atasan_name)): ?><span class="fw-bold" style="color: var(--primary);">(<?= htmlspecialchars($ticket->it_atasan_name) ?>)</span><?php endif; ?></div>
                                <div class="text-slate-500" style="font-size: 12px;"><?= date('d M Y - H:i', strtotime($ticket->it_approved_at)) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($ticket->resolved_at) && $ticket->resolved_at): ?>
                        <div class="d-flex align-items-center gap-3 text-slate-800">
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <span class="material-symbols-outlined text-success" style="font-size: 16px;">task_alt</span>
                            </div>
                            <div>
                                <div class="fw-medium text-success" style="font-size: 13px;">Diselesaikan <?php if(!empty($ticket->resolver_name)): ?><span class="fw-bold" style="color: var(--primary);">(<?= htmlspecialchars($ticket->resolver_name) ?>)</span><?php endif; ?></div>
                                <div class="text-slate-500" style="font-size: 12px;"><?= date('d M Y - H:i', strtotime($ticket->resolved_at)) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($ticket->rejected_at) && $ticket->rejected_at): ?>
                        <div class="d-flex align-items-center gap-3 text-slate-800">
                            <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <span class="material-symbols-outlined text-danger" style="font-size: 16px;">cancel</span>
                            </div>
                            <div>
                                <div class="fw-medium text-danger" style="font-size: 13px;">Ditolak <?php if(!empty($ticket->rejecter_name)): ?><span class="fw-bold" style="color: var(--primary);">(<?= htmlspecialchars($ticket->rejecter_name) ?>)</span><?php endif; ?></div>
                                <div class="text-slate-500" style="font-size: 12px;"><?= date('d M Y - H:i', strtotime($ticket->rejected_at)) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>

                    <hr style="border-color: rgba(0,0,0,0.1); margin: 0;">

                    <!-- Attachment -->
                    <div>
                        <p class="text-slate-400 fw-semibold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 1px;">Lampiran Gambar</p>
                        <?php if($ticket->attachment): ?>
                            <a href="<?= base_url('uploads/tickets/' . $ticket->attachment) ?>" data-fancybox="gallery" class="d-block overflow-hidden rounded-3 shadow-sm border position-relative" style="border-color: rgba(255,255,255,0.8) !important; cursor: zoom-in; background: #000;">
                                <img src="<?= base_url('uploads/tickets/' . $ticket->attachment) ?>" alt="Lampiran" class="img-fluid w-100 opacity-75" style="object-fit: cover; max-height: 200px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'; this.classList.remove('opacity-75')" onmouseout="this.style.transform='scale(1)'; this.classList.add('opacity-75')">
                                <div class="position-absolute top-50 start-50 translate-middle text-white d-flex flex-column align-items-center pointer-events-none" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                                    <span class="material-symbols-outlined mb-1" style="font-size: 32px;">zoom_in</span>
                                    <span style="font-size: 12px; font-weight: 500;">Klik untuk memperbesar</span>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="d-flex align-items-center gap-2 text-slate-500 bg-light p-3 rounded-3" style="background: rgba(0,0,0,0.02) !important; border: 1px dashed rgba(0,0,0,0.1);">
                                <span class="material-symbols-outlined" style="font-size: 20px;">image_not_supported</span>
                                <span style="font-size: 13px;">Tidak ada lampiran disertakan.</span>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Main Content Column (Deskripsi & Perbaikan) -->
        <div class="col-lg-8">
            <div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm h-100">
                <div class="px-4 py-3 border-bottom d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
                    <span class="material-symbols-outlined text-primary">engineering</span>
                    <h5 class="mb-0 fw-bold text-slate-800" style="font-size: 16px;">Uraian Kendala & Solusi</h5>
                </div>
                
                <div class="p-4 d-flex flex-column gap-4 flex-grow-1">
                    
                    <!-- Problem Description -->
                    <div class="bg-primary bg-opacity-10 p-4 p-md-5 rounded-4 border border-primary border-opacity-25 position-relative overflow-hidden">
                        <span class="material-symbols-outlined position-absolute text-primary opacity-10" style="top: -10px; right: 10px; font-size: 120px;">format_quote</span>
                        
                        <div class="position-relative z-1">
                            <p class="text-primary fw-bold text-uppercase mb-1 d-flex align-items-center gap-1" style="font-size: 12px; letter-spacing: 1px;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">title</span> Judul Permintaan
                            </p>
                            <h3 class="text-slate-900 fw-bold mb-4" style="font-size: 22px;">
                                <?= htmlspecialchars($ticket->title) ?>
                            </h3>
                            
                            <hr class="border-primary border-opacity-25 my-4">
                            
                            <p class="text-primary fw-bold text-uppercase mb-2 d-flex align-items-center gap-1" style="font-size: 12px; letter-spacing: 1px;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">notes</span> Deskripsi Kendala Lengkap
                            </p>
                            <div class="text-slate-800" style="font-size: 16px; line-height: 1.8;">
                                <?= nl2br(htmlspecialchars($ticket->description)) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Resolution Description -->
                    <div class="mt-2 flex-grow-1 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-slate-600">medical_services</span>
                            <h4 class="h6 fw-bold text-slate-800 mb-0">Tindakan / Laporan Perbaikan IT</h4>
                        </div>
                        
                        <div class="bg-success bg-opacity-10 p-4 p-md-5 rounded-4 border border-success border-opacity-25 flex-grow-1 position-relative overflow-hidden">
                            <span class="material-symbols-outlined position-absolute text-success opacity-10" style="bottom: -20px; right: 10px; font-size: 150px;">check_circle</span>
                            
                            <div class="position-relative z-1">
                                <?php if($ticket->status == 'resolved' || !empty($ticket->it_notes)): ?>
                                    <div class="text-slate-800" style="font-size: 16px; line-height: 1.8;">
                                        <?= nl2br(htmlspecialchars($ticket->it_notes ?? 'Tidak ada catatan perbaikan tambahan.')) ?>
                                    </div>
                                <?php elseif($ticket->status == 'rejected'): ?>
                                    <div class="d-flex align-items-center gap-2 text-danger opacity-75">
                                        <span class="material-symbols-outlined" style="font-size: 24px;">block</span>
                                        <span style="font-size: 15px; font-weight: 500;">Tiket ini telah ditolak dan tidak dikerjakan.</span>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex flex-column align-items-center justify-content-center text-center py-4 text-success opacity-75">
                                        <span class="material-symbols-outlined mb-2" style="font-size: 40px; animation: pulse 2s infinite;">build_circle</span>
                                        <span style="font-size: 15px; font-weight: 500;">Tim IT belum selesai melakukan penanganan atau belum memberikan catatan perbaikan.</span>
                                        <style>
                                            @keyframes pulse {
                                                0% { opacity: 0.5; transform: scale(0.95); }
                                                50% { opacity: 0.8; transform: scale(1.05); }
                                                100% { opacity: 0.5; transform: scale(0.95); }
                                            }
                                        </style>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('ticket/layout/footer'); ?>
