<?php $this->load->view('ticket/layout/header'); ?>

<div class="glass-card-container mx-auto" style="max-width: 1000px;">
    <div class="glass-card position-relative overflow-hidden w-100">
        <div class="px-4 py-4 border-bottom position-relative z-1 d-flex flex-column gap-1" style="border-color: rgba(255,255,255,0.6) !important;">
            <div class="d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-primary" style="font-size: 28px;">history</span>
                <h1 class="h4 fw-bold text-slate-900 mb-0">Riwayat Pengajuan Tiket</h1>
            </div>
            <p class="text-slate-600 mb-0" style="font-size: 14px;">Daftar seluruh tiket bantuan IT yang pernah Anda ajukan.</p>
        </div>

        <div class="px-4 py-4 position-relative z-1 table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0 datatable-glass w-100 text-nowrap" id="tableHistory" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="15%">ID Tiket</th>
                        <th width="30%">Permintaan</th>
                        <th width="10%">Lampiran</th>
                        <th width="15%">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($tickets)): foreach($tickets as $t): ?>
                        <tr>
                            <td class="text-slate-600 fw-medium" style="font-size: 13px;"><?= date('d/m/Y H:i', strtotime($t->created_at)) ?></td>
                            <td><span class="text-primary fw-bold" style="font-size: 13px;">#IT-<?= strtoupper(substr($t->id, 0, 8)) ?></span></td>
                            <td>
                                <div class="text-truncate text-slate-900 fw-bold" style="max-width: 250px; font-size: 14px;">
                                    <?= htmlspecialchars($t->title) ?>
                                </div>
                                <div class="text-truncate text-slate-500 mt-1" style="max-width: 250px; font-size: 12px;">
                                    <?= htmlspecialchars($t->description) ?>
                                </div>
                            </td>
                            <td>
                                <?php if($t->attachment): ?>
                                    <a href="<?= base_url('uploads/tickets/' . $t->attachment) ?>" data-fancybox="gallery-history" class="btn btn-sm btn-primary-custom d-inline-flex align-items-center justify-content-center gap-1 px-2 py-1" style="font-size: 12px; border-radius: 6px;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">image</span> Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-400" style="font-size: 12px;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $badge_class = 'bg-secondary';
                                $status_text = 'Pending';
                                if ($t->status == 'pending') { $badge_class = 'bg-warning text-dark'; $status_text = 'Menunggu Atasan'; }
                                elseif ($t->status == 'pending_it') { $badge_class = 'bg-info text-dark'; $status_text = 'Menunggu IT'; }
                                elseif ($t->status == 'in_progress') { $badge_class = 'bg-primary'; $status_text = 'Dikerjakan IT'; }
                                elseif ($t->status == 'resolved') { $badge_class = 'bg-success'; $status_text = 'Selesai'; }
                                elseif ($t->status == 'rejected') { $badge_class = 'bg-danger'; $status_text = 'Ditolak'; }
                                ?>
                                <span class="badge badge-status <?= $badge_class ?>"><?= $status_text ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('detail-tiket/' . $t->id) ?>" class="btn btn-sm btn-primary-custom d-inline-flex align-items-center justify-content-center gap-1 w-100" style="font-size: 12px; border-radius: 6px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('ticket/layout/footer'); ?>
<script>
    $(document).ready(function() {
        $('#tableHistory').DataTable({
            responsive: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[0, 'desc']],
            pageLength: 10,
            dom: '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3"lf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-3"ip>'
        });
    });
</script>
