    </div>
    <!-- End Main Content wrapper -->

    <!-- Mobile Bottom Navigation (Hidden on Desktop) -->
    <div class="d-flex d-md-none fixed-bottom bg-white border-top align-items-center justify-content-around shadow-lg" style="height: 65px; z-index: 1040; padding-bottom: env(safe-area-inset-bottom);">
        <a href="<?= base_url('buat-tiket') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center w-100 h-100 mobile-nav-item <?= (current_url() == base_url('buat-tiket') || current_url() == base_url('ticket')) ? 'active' : '' ?>">
            <span class="material-symbols-outlined" style="font-size: 24px;">add_circle</span>
            <span style="font-size: 10px; font-weight: 600; margin-top: 2px;">Pengajuan</span>
        </a>
        <a href="<?= base_url('riwayat-tiket') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center w-100 h-100 mobile-nav-item <?= current_url() == base_url('riwayat-tiket') ? 'active' : '' ?>">
            <span class="material-symbols-outlined" style="font-size: 24px;">history</span>
            <span style="font-size: 10px; font-weight: 600; margin-top: 2px;">Riwayat</span>
        </a>
        <?php if (isset($is_atasan) && $is_atasan): ?>
        <a href="<?= base_url('persetujuan') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center w-100 h-100 position-relative mobile-nav-item <?= current_url() == base_url('persetujuan') ? 'active' : '' ?>">
            <div class="position-relative">
                <span class="material-symbols-outlined" style="font-size: 24px;">fact_check</span>
                <?php if (isset($pending_approval_count) && $pending_approval_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px; padding: 0.25em 0.4em;">
                        <?= $pending_approval_count ?>
                    </span>
                <?php endif; ?>
            </div>
            <span style="font-size: 10px; font-weight: 600; margin-top: 2px;">Persetujuan</span>
        </a>
        <?php endif; ?>
        <a href="<?= base_url('akun') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center w-100 h-100 mobile-nav-item <?= current_url() == base_url('akun') ? 'active' : '' ?>">
            <span class="material-symbols-outlined" style="font-size: 24px;">person</span>
            <span style="font-size: 10px; font-weight: 600; margin-top: 2px;">Profil</span>
        </a>
        <a href="<?= base_url('logout') ?>" class="text-decoration-none d-flex flex-column align-items-center justify-content-center w-100 h-100 mobile-nav-item danger-item">
            <span class="material-symbols-outlined" style="font-size: 24px;">logout</span>
            <span style="font-size: 10px; font-weight: 600; margin-top: 2px;">Keluar</span>
        </a>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <!-- Fancybox JS -->
    <script src="<?= base_url('assets/js/fancybox.umd.js') ?>"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            // Your custom options
        });
    </script>
</body>
</html>
