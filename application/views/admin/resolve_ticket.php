<?php $this->load->view('admin/layout/header'); ?>

<div class="container-fluid px-3 px-md-4 mb-5">
    
    <!-- Title Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('admin/tiket') ?>" class="btn btn-light d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 42px; height: 42px;">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="h4 fw-bold text-slate-800 mb-0">Penyelesaian Tiket</h2>
                <span class="text-slate-500 font-monospace" style="font-size: 14px;">#IT-<?= strtoupper(substr($ticket->id, 0, 6)) ?></span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Ticket Details Column -->
        <div class="col-lg-5 col-xl-4">
            <div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm h-100">
                <div class="px-4 py-3 border-bottom d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
                    <span class="material-symbols-outlined text-info">info</span>
                    <h5 class="mb-0 fw-bold text-slate-800" style="font-size: 16px;">Detail Tiket</h5>
                </div>
                <div class="p-4 d-flex flex-column gap-4 flex-grow-1">
                    
                    <!-- Applicant Info -->
                    <div>
                        <p class="text-slate-400 fw-semibold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 1px;">Pemohon</p>
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

                    <!-- Time Info -->
                    <div>
                        <p class="text-slate-400 fw-semibold text-uppercase mb-1" style="font-size: 11px; letter-spacing: 1px;">Waktu Dibuat</p>
                        <div class="d-flex align-items-center gap-2 text-slate-800">
                            <span class="material-symbols-outlined text-slate-400" style="font-size: 18px;">schedule</span>
                            <span class="fw-medium" style="font-size: 15px;"><?= date('l, d M Y - H:i', strtotime($ticket->created_at)) ?></span>
                        </div>
                    </div>

                    <hr style="border-color: rgba(0,0,0,0.1); margin: 0;">

                    <!-- Attachment -->
                    <div>
                        <p class="text-slate-400 fw-semibold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 1px;">Lampiran</p>
                        <?php if($ticket->attachment): ?>
                            <a href="<?= base_url('uploads/tickets/' . $ticket->attachment) ?>" data-fancybox="gallery" class="d-block overflow-hidden rounded-3 shadow-sm border" style="border-color: rgba(255,255,255,0.8) !important; cursor: zoom-in;">
                                <img src="<?= base_url('uploads/tickets/' . $ticket->attachment) ?>" alt="Lampiran" class="img-fluid w-100" style="object-fit: cover; max-height: 200px; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        <?php else: ?>
                            <div class="d-flex align-items-center gap-2 text-slate-500 bg-light p-3 rounded-3" style="background: rgba(0,0,0,0.02) !important; border: 1px dashed rgba(0,0,0,0.1);">
                                <span class="material-symbols-outlined" style="font-size: 20px;">image_not_supported</span>
                                <span style="font-size: 14px;">Tidak ada lampiran disertakan.</span>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Problem & Resolution Column -->
        <div class="col-lg-7 col-xl-8">
            <div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm h-100">
                <div class="px-4 py-3 border-bottom d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
                    <span class="material-symbols-outlined text-primary">engineering</span>
                    <h5 class="mb-0 fw-bold text-slate-800" style="font-size: 16px;">Kendala & Tindakan Perbaikan</h5>
                </div>
                
                <div class="p-4 d-flex flex-column gap-4 flex-grow-1">
                    
                    <!-- User Problem Description -->
                    <div class="bg-primary bg-opacity-10 p-4 rounded-4 border border-primary border-opacity-25 position-relative">
                        <span class="material-symbols-outlined position-absolute text-primary opacity-25" style="top: 15px; right: 15px; font-size: 48px;">format_quote</span>
                        <p class="text-primary fw-semibold text-uppercase mb-2" style="font-size: 12px; letter-spacing: 1px;">Judul Permintaan dari User</p>
                        <p class="text-slate-900 fw-bold mb-4 position-relative z-1" style="font-size: 18px;">
                            <?= htmlspecialchars($ticket->title) ?>
                        </p>
                        
                        <p class="text-primary fw-semibold text-uppercase mb-2" style="font-size: 12px; letter-spacing: 1px;">Deskripsi Kendala dari User</p>
                        <p class="text-slate-800 mb-0 position-relative z-1" style="font-size: 16px; line-height: 1.6;">
                            <?= nl2br(htmlspecialchars($ticket->description)) ?>
                        </p>
                    </div>

                    <!-- Resolution Form -->
                    <div class="mt-2 flex-grow-1 d-flex flex-column">
                        <?php echo form_open('admin/proses-tiket/' . $ticket->id, ['class' => 'd-flex flex-column flex-grow-1 h-100']); ?>
                            
                            <label for="it_notes" class="form-label fw-bold text-slate-800 mb-2" style="font-size: 15px;">Keterangan Perbaikan <span class="text-danger">*</span></label>
                            <p class="text-slate-500 mb-3" style="font-size: 13px;">Silakan tulis dengan jelas tindakan apa saja yang telah dilakukan untuk menyelesaikan tiket ini. Catatan ini akan dapat dibaca oleh pemohon.</p>
                            
                            <textarea class="form-control-glass flex-grow-1 mb-4" id="it_notes" name="it_notes" required placeholder="Tuliskan tindakan, laporan perbaikan, atau catatan tambahan terkait penyelesaian tiket ini secara spesifik..." style="min-height: 200px; font-size: 15px; padding: 1.25rem; line-height: 1.6; resize: vertical; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);"></textarea>

                            <div class="d-flex align-items-center justify-content-end gap-3 pt-3 border-top" style="border-color: rgba(0,0,0,0.1) !important;">
                                <a href="<?= base_url('admin/tiket') ?>" class="btn btn-light px-4 py-2 fw-medium rounded-pill">Batal</a>
                                <button type="submit" class="btn btn-success px-4 py-2 fw-bold d-flex align-items-center gap-2 rounded-pill shadow-sm" style="font-size: 15px;">
                                    <span class="material-symbols-outlined" style="font-size: 20px;">check_circle</span>
                                    Submit & Selesaikan
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/layout/footer'); ?>
