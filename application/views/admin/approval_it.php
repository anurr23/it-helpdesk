<?php $this->load->view('admin/layout/header'); ?>

<!-- Tab Content: Persetujuan IT -->
<div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm">
    <div class="px-4 py-3 border-bottom d-flex align-items-center gap-2" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
        <span class="material-symbols-outlined text-danger">fact_check</span>
        <h5 class="mb-0 fw-bold text-slate-800">Daftar Tiket Menunggu Persetujuan IT</h5>
    </div>
    <div class="p-0 table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0 datatable-glass" style="width:100%">
            <thead style="background: rgba(255,255,255,0.4);">
                <tr>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Waktu & ID</th>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Pemohon</th>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Permintaan</th>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Lampiran</th>
                    <th class="text-slate-500 fw-semibold text-uppercase text-end px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($pending_tickets)): ?>
                    <?php foreach($pending_tickets as $ticket): ?>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td class="px-4 py-3">
                            <div class="fw-semibold text-slate-900" style="font-size: 14px;"><?= date('d M Y, H:i', strtotime($ticket->created_at)) ?></div>
                            <div class="text-slate-500 font-monospace" style="font-size: 12px;">#IT-<?= strtoupper(substr($ticket->id, 0, 6)) ?></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                    <?= strtoupper(substr($ticket->user_name, 0, 2)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold text-slate-900" style="font-size: 14px;"><?= htmlspecialchars($ticket->user_name) ?></div>
                                    <div class="text-slate-500" style="font-size: 12px;"><?= htmlspecialchars($ticket->user_email) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3" style="max-width: 300px;">
                            <div class="text-truncate text-slate-900 fw-bold mb-1" style="font-size: 14px;">
                                <?= htmlspecialchars($ticket->title) ?>
                            </div>
                            <p class="mb-0 text-slate-700 text-truncate" title="<?= htmlspecialchars($ticket->description) ?>" style="font-size: 13px;">
                                <?= htmlspecialchars($ticket->description) ?>
                            </p>
                        </td>
                        <td class="px-4 py-3">
                            <?php if($ticket->attachment): ?>
                                <a href="<?= base_url('uploads/tickets/' . $ticket->attachment) ?>" data-fancybox="gallery-active" class="btn btn-sm btn-primary-custom d-inline-flex align-items-center justify-content-center gap-1 px-2 py-1" style="font-size: 12px; border-radius: 6px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">image</span> Lihat
                                </a>
                            <?php else: ?>
                                <span class="text-slate-400" style="font-size: 12px;">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end px-4 py-3">
                            <a href="<?= base_url('persetujuan/it/' . $ticket->id) ?>" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 px-3">
                                <span class="material-symbols-outlined" style="font-size: 16px;">fact_check</span> Proses
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('admin/layout/footer'); ?>
