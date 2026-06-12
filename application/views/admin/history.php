<?php $this->load->view('admin/layout/header'); ?>
<style>


/* Custom Flatpickr Glassmorphism */
.flatpickr-calendar {
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(10px) !important;
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    border-radius: 12px !important;
}
.flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
    background: #0ea5e9 !important;
    border-color: #0ea5e9 !important;
}

/* DataTables Footer Input Styling */
.dt-footer-filter {
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 13px;
    width: 100%;
    transition: all 0.3s;
}
.dt-footer-filter:focus {
    outline: none;
    background: #fff;
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}
</style>

<!-- Tab Content: History Tickets -->
<div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm">
    <div class="px-4 py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-slate-500">history</span>
            <h5 class="mb-0 fw-bold text-slate-700"><?= $title ?></h5>
        </div>
    </div>
    <div class="p-0 table-responsive">
        <table id="historyTable" class="table table-hover table-borderless align-middle mb-0 datatable-glass" style="width:100%">
            <thead style="background: rgba(255,255,255,0.4);">
                <tr>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Waktu Diselesaikan</th>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Waktu & ID (Dibuat)</th>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Pemohon</th>
                    <?php if(isset($is_it_manager) && $is_it_manager): ?>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Ditangani Oleh</th>
                    <?php endif; ?>
                    <th class="text-slate-500 fw-semibold text-uppercase px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Status</th>
                    <th class="text-slate-500 fw-semibold text-uppercase text-end px-4 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($history_tickets)): ?>
                    <?php foreach($history_tickets as $ticket): ?>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td class="px-4 py-3">
                            <div class="fw-semibold text-slate-700" style="font-size: 14px;">
                                <?php 
                                    if($ticket->status == 'resolved' && isset($ticket->resolved_at)) {
                                        echo date('d M Y, H:i', strtotime($ticket->resolved_at));
                                    } elseif($ticket->status == 'rejected' && isset($ticket->rejected_at)) {
                                        echo date('d M Y, H:i', strtotime($ticket->rejected_at));
                                    } else {
                                        echo '-';
                                    }
                                ?>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="fw-medium text-slate-600" style="font-size: 13px;"><?= date('d M Y, H:i', strtotime($ticket->created_at)) ?></div>
                            <div class="text-slate-400 font-monospace" style="font-size: 11px;">#IT-<?= strtoupper(substr($ticket->id, 0, 6)) ?></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="fw-medium text-slate-700" style="font-size: 14px;"><?= htmlspecialchars($ticket->user_name) ?></div>
                        </td>
                        <?php if(isset($is_it_manager) && $is_it_manager): ?>
                        <td class="px-4 py-3">
                            <div class="fw-medium text-primary-custom" style="font-size: 14px;"><span class="material-symbols-outlined align-middle me-1" style="font-size: 16px;">support_agent</span><?= htmlspecialchars($ticket->resolver_name ?? 'Tim IT') ?></div>
                        </td>
                        <?php endif; ?>
                        <td class="px-4 py-3">
                            <span class="badge badge-status bg-success"><span class="material-symbols-outlined align-middle me-1" style="font-size: 14px;">check_circle</span>Selesai</span>
                        </td>
                        <td class="text-end px-4 py-3">
                            <a href="<?= base_url('admin/detail-tiket/' . $ticket->id) ?>" class="btn btn-info btn-sm text-white d-inline-flex align-items-center gap-1 px-3">
                                <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot style="background: rgba(255,255,255,0.4);">
                <tr>
                    <th class="px-3 py-2"><input type="text" class="dt-footer-filter" placeholder="Cari Waktu..." /></th>
                    <th class="px-3 py-2"><input type="text" class="dt-footer-filter" placeholder="Cari Waktu/ID..." /></th>
                    <th class="px-3 py-2"><input type="text" class="dt-footer-filter" placeholder="Cari Pemohon..." /></th>
                    <?php if(isset($is_it_manager) && $is_it_manager): ?>
                    <th class="px-3 py-2"><input type="text" class="dt-footer-filter" placeholder="Cari IT..." /></th>
                    <?php endif; ?>
                    <th class="px-3 py-2"><input type="text" class="dt-footer-filter" placeholder="Cari Status..." /></th>
                    <th class="px-3 py-2"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php $this->load->view('admin/layout/footer'); ?>

<script>
$(document).ready(function() {
    // Custom DataTables logic for footer filter (since datatable-glass is handled in footer.php)
    var table;
    if ($.fn.DataTable.isDataTable('#historyTable')) {
        table = $('#historyTable').DataTable();
    } else {
        // Fallback initialization if footer.php missed it
        table = $('#historyTable').DataTable({
            "language": {
                "emptyTable": "Tidak ada data tiket",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ tiket",
                "infoEmpty": "Menampilkan 0 hingga 0 dari 0 tiket",
                "infoFiltered": "(disaring dari _MAX_ total tiket)",
                "lengthMenu": "Tampilkan _MENU_ tiket",
                "search": "Cari Cepat:",
                "zeroRecords": "Tidak ditemukan tiket yang sesuai"
            },
            "order": [],
            "pageLength": 10
        });
    }

    // Setup Footer Filters
    table.columns().every(function () {
        var that = this;
        var footerInput = $('input', this.footer());
        if (footerInput.length) {
            footerInput.on('keyup change clear', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        }
    });
});
</script>
