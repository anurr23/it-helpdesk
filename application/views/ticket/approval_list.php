<?php $this->load->view('ticket/layout/header'); ?>

<div class="glass-card-container mx-auto" style="max-width: 1000px;">
    <div class="glass-card position-relative overflow-hidden w-100">
        <div class="px-4 py-4 border-bottom position-relative z-1 d-flex flex-column gap-1" style="border-color: rgba(255,255,255,0.6) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-primary" style="font-size: 28px;">fact_check</span>
                    <h1 class="h4 fw-bold text-slate-900 mb-0">Persetujuan Tiket</h1>
                </div>
                <?php if (isset($pending_approval_count) && $pending_approval_count > 0): ?>
                    <span class="badge badge-notification fs-6 px-3 py-2"><?= $pending_approval_count ?> Tiket Menunggu</span>
                <?php endif; ?>
            </div>
            <p class="text-slate-600 mb-0 mt-2" style="font-size: 14px;">Daftar pengajuan tiket bantuan IT dari bawahan Anda yang membutuhkan persetujuan.</p>
        </div>

        <div class="px-4 py-4 position-relative z-1 table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0 datatable-glass w-100 text-nowrap" id="tableApproval" style="width: 100%;">
                <thead>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Pemohon</th>
                        <th width="35%">Permintaan</th>
                        <th width="20%">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($tickets)): foreach($tickets as $t): ?>
                        <tr>
                            <td class="text-slate-600 fw-medium" style="font-size: 13px;"><?= date('d/m/Y H:i', strtotime($t->created_at)) ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-slate-900 fw-bold" style="font-size: 14px;"><?= htmlspecialchars($t->user_name) ?></span>
                                    <span class="text-slate-500" style="font-size: 12px;">#IT-<?= strtoupper(substr($t->id, 0, 8)) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate text-slate-900 fw-bold" style="max-width: 250px; font-size: 14px;">
                                    <?= htmlspecialchars($t->title) ?>
                                </div>
                                <div class="text-truncate text-slate-500 mt-1" style="max-width: 250px; font-size: 12px;">
                                    <?= htmlspecialchars($t->description) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-status bg-warning">Menunggu Persetujuan</span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('persetujuan/guest/' . $t->id) ?>" class="btn btn-sm btn-primary-custom d-inline-flex align-items-center justify-content-center gap-1 w-100" style="font-size: 12px; padding: 0.4rem 0.5rem;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">gavel</span> Tinjau
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
        $('#tableApproval').DataTable({
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
