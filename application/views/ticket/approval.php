<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Persetujuan Permintaan Layanan IT</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= base_url('assets/css/material-symbols.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/inter.css') ?>" rel="stylesheet">
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/fancybox.css') ?>" />
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background: linear-gradient(135deg, #ffffff, #dbeafe);
        }
        .blob-bg {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.4;
            z-index: 0;
            mix-blend-mode: multiply;
        }
    </style>
</head>
<body class="text-slate-900 min-vh-100 d-flex flex-column antialiased position-relative overflow-hidden">
    
    <!-- Decorative background elements -->
    <div class="blob-bg bg-primary" style="top: -10%; left: -10%; width: 384px; height: 384px;"></div>
    <div class="blob-bg bg-info" style="top: -10%; right: -10%; width: 384px; height: 384px;"></div>
    <div class="blob-bg" style="background: #bae6fd; bottom: -20%; left: 20%; width: 384px; height: 384px;"></div>

    <main class="flex-grow-1 d-flex align-items-center justify-content-center p-3 position-relative z-1">
        
        <div class="glass-card w-100 overflow-hidden d-flex flex-column" style="max-width: 672px;">
            
            <!-- Header Area -->
            <div class="px-4 py-4 text-center border-bottom" style="background: rgba(255,255,255,0.5); border-color: rgba(255,255,255,0.6) !important;">
                <span class="material-symbols-outlined text-primary-custom mb-2" style="font-size: 36px; font-variation-settings: 'FILL' 1;">admin_panel_settings</span>
                <h1 class="h4 fw-bold text-primary-custom mb-1">Persetujuan Permintaan Layanan IT</h1>
                <p class="text-slate-600 mb-0" style="font-size: 14px;">Tinjau detail tiket di bawah ini untuk memberikan keputusan.</p>
            </div>

            <!-- Details Section -->
            <div class="p-4 d-flex flex-column gap-3">
                
                <!-- Pemohon -->
                <div class="row g-2 align-items-start border-bottom pb-3" style="border-color: rgba(255,255,255,0.6) !important;">
                    <div class="col-md-4 text-slate-500 fw-semibold text-uppercase" style="font-size: 12px; letter-spacing: 0.05em;">Pemohon</div>
                    <div class="col-md-8 d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: var(--primary); font-size: 18px;">
                            <?= strtoupper(substr($ticket->user_name, 0, 2)) ?>
                        </div>
                        <div>
                            <div class="fw-medium text-slate-900" style="font-size: 16px;"><?= htmlspecialchars($ticket->user_name) ?></div>
                            <div class="text-slate-500" style="font-size: 14px;"><?= htmlspecialchars($ticket->user_email) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Waktu Pengajuan -->
                <div class="row g-2 align-items-start border-bottom pb-3" style="border-color: rgba(255,255,255,0.6) !important;">
                    <div class="col-md-4 text-slate-500 fw-semibold text-uppercase" style="font-size: 12px; letter-spacing: 0.05em;">Waktu Pengajuan</div>
                    <div class="col-md-8">
                        <span class="badge rounded-pill text-slate-600 fw-semibold d-inline-flex align-items-center gap-1 px-3 py-2 shadow-sm" style="background: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.6); font-size: 12px;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">schedule</span>
                            <?= date('d M Y, H:i', strtotime($ticket->created_at)) ?>
                        </span>
                    </div>
                </div>

                <!-- Deskripsi Masalah -->
                <div class="row g-2 align-items-start border-bottom pb-3" style="border-color: rgba(255,255,255,0.6) !important;">
                    <div class="col-md-4 text-slate-500 fw-semibold text-uppercase" style="font-size: 12px; letter-spacing: 0.05em;">Deskripsi Masalah</div>
                    <div class="col-md-8">
                        <p class="text-slate-900 p-2 rounded-3 shadow-sm mb-0" style="background: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.6); font-size: 16px; white-space: pre-wrap;">
                            <?= htmlspecialchars($ticket->description) ?>
                        </p>
                    </div>
                </div>

                <!-- Lampiran -->
                <?php if($ticket->attachment): ?>
                <div class="row g-2 align-items-start pb-3">
                    <div class="col-md-4 text-slate-500 fw-semibold text-uppercase" style="font-size: 12px; letter-spacing: 0.05em;">Lampiran</div>
                    <div class="col-md-8">
                        <a href="<?= base_url('uploads/tickets/' . $ticket->attachment) ?>" data-fancybox="gallery" class="text-decoration-none d-inline-flex align-items-center gap-2 p-2 rounded-3 shadow-sm w-100" style="background: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.6); transition: background 0.2s;">
                            <div class="rounded-2 d-flex align-items-center justify-content-center text-primary shadow-sm" style="width: 32px; height: 32px; background: rgba(0,61,155,0.1);">
                                <span class="material-symbols-outlined" style="font-size: 18px;">image</span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-primary-custom fw-medium" style="font-size: 14px;"><?= htmlspecialchars($ticket->attachment) ?></span>
                                <span class="text-slate-400" style="font-size: 12px; font-weight: 600;">Lihat Gambar</span>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- Action Area -->
            <?php echo form_open($process_url, ['class' => 'p-4 d-flex flex-column flex-sm-row gap-3 justify-content-end align-items-center border-top', 'style' => 'background: rgba(255,255,255,0.5); border-color: rgba(255,255,255,0.6) !important;']); ?>
                <button type="submit" name="status" value="rejected" class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-1 w-100 w-sm-auto px-4 py-2" style="border-radius: 0.5rem; font-weight: 600; font-size: 12px; background: rgba(255,255,255,0.6);">
                    <span class="material-symbols-outlined" style="font-size: 20px;">close</span> Tolak
                </button>
                <button type="submit" name="status" value="approved" class="btn btn-success d-flex align-items-center justify-content-center gap-1 w-100 w-sm-auto px-4 py-2" style="border-radius: 0.5rem; font-weight: 600; font-size: 12px; background: #006c47; border-color: #006c47;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">check</span> Setujui
                </button>
            <?php echo form_close(); ?>


        </div>

    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- Fancybox JS -->
    <script src="<?= base_url('assets/js/fancybox.umd.js') ?>"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            // Fancybox options
        });
    </script>
</body>
</html>
