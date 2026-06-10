        </div>
    </main>

    <!-- Bottom Nav (Mobile Only) -->
    <nav class="fixed-bottom d-md-none d-flex justify-content-around align-items-center py-2 px-3 border-top z-3" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(15px);">
        <a href="<?= base_url('admin/beranda') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center p-1 <?= ($this->uri->segment(2) == 'overview' || $this->uri->segment(2) == '') ? 'text-primary' : 'text-slate-400' ?>">
            <span class="material-symbols-outlined" style="font-size: 24px;">dashboard</span>
            <span style="font-size: 10px; font-weight: 600;">Dashboard</span>
        </a>
        <a href="<?= base_url('admin/tiket') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center p-1 <?= ($this->uri->segment(2) == 'dashboard') ? 'text-primary' : 'text-slate-400' ?>">
            <span class="material-symbols-outlined" style="font-size: 24px;">monitoring</span>
            <span style="font-size: 10px; font-weight: 600;">Status</span>
        </a>
        <a href="<?= base_url('admin/riwayat') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center p-1 <?= ($this->uri->segment(2) == 'history') ? 'text-primary' : 'text-slate-400' ?>">
            <span class="material-symbols-outlined" style="font-size: 24px;">history</span>
            <span style="font-size: 10px; font-weight: 600;">Riwayat</span>
        </a>
        <a href="<?= base_url('admin/pengguna') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center p-1 <?= ($this->uri->segment(2) == 'users') ? 'text-primary' : 'text-slate-400' ?>">
            <span class="material-symbols-outlined" style="font-size: 24px;">group</span>
            <span style="font-size: 10px; font-weight: 600;">Users</span>
        </a>
        <a href="<?= base_url('admin/departemen') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center p-1 <?= ($this->uri->segment(2) == 'departments') ? 'text-primary' : 'text-slate-400' ?>">
            <span class="material-symbols-outlined" style="font-size: 24px;">domain</span>
            <span style="font-size: 10px; font-weight: 600;">Depts</span>
        </a>
    </nav>

    <!-- jQuery -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <!-- Bootstrap Bundle -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- DataTables JS -->
    <script src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
    <!-- DataTables Bootstrap 5 -->
    <script src="<?= base_url('assets/js/dataTables.bootstrap5.min.js') ?>"></script>
    <!-- Flatpickr JS -->
    <script src="<?= base_url('assets/js/flatpickr.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/flatpickr-id.js') ?>"></script>
    <!-- Select2 JS -->
    <script src="<?= base_url('assets/js/select2.min.js') ?>"></script>
    <!-- Fancybox JS -->
    <script src="<?= base_url('assets/js/fancybox.umd.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Init Fancybox
            if(typeof Fancybox !== 'undefined') {
                Fancybox.bind("[data-fancybox]", {});
            }

            // Move all modals to body to prevent stacking context (z-index) issues
            $('.modal').appendTo('body');

            // Init DataTables
            $('.datatable-glass').DataTable({
                "language": {
                    "url": "<?= base_url('assets/js/datatables-id.json') ?>"
                },
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                       "<'row'<'col-sm-12'tr>>" +
                       "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
            });

            // Init Select2 globally for any element with .select2-glass class
            $('.select2-glass').select2({
                width: '100%',
                minimumResultsForSearch: 10
            });
        });
    </script>
</body>
</html>
