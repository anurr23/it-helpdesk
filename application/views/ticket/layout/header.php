<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'IT Helpdesk' ?></title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= base_url('assets/css/material-symbols.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/inter.css') ?>" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/fancybox.css') ?>" />
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . time()) ?>">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        /* Premium Topbar Glassmorphism */
        .topbar-glass {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            padding: 0.75rem 0;
            z-index: 1030;
        }
        
        .navbar-brand-premium {
            font-weight: 800;
            background: linear-gradient(135deg, var(--blue-700), var(--blue-600));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Desktop Tab Panel Style (Above Card) */
        .desktop-tab-panel {
            display: flex;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            padding: 0.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            margin-bottom: 1.5rem;
            max-width: 672px; /* Matching form width */
            margin-left: auto;
            margin-right: auto;
        }
        /* Wider for history/approval pages */
        .glass-card-container .desktop-tab-panel {
            max-width: 1000px; 
        }

        .tab-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            color: var(--slate-600);
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid transparent;
        }
        
        .tab-btn:hover {
            color: var(--blue-700);
            background: rgba(255, 255, 255, 0.8);
            border-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .tab-btn.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
            border-color: var(--primary);
        }

        .badge-notification-tab {
            background: #ef4444;
            color: white;
            border-radius: 50rem;
            padding: 0.15em 0.5em;
            font-size: 0.75rem;
            margin-left: 0.25rem;
        }
        .tab-btn.active .badge-notification-tab {
            background: white;
            color: var(--primary);
        }
        
        /* Mobile Bottom Nav Styles */
        .mobile-nav-item {
            color: var(--slate-500) !important;
            transition: all 0.2s ease;
        }
        .mobile-nav-item:hover {
            color: var(--slate-700) !important;
        }
        .mobile-nav-item.active {
            color: var(--primary) !important;
        }
        .mobile-nav-item.active .material-symbols-outlined {
            font-variation-settings: 'FILL' 1;
        }
        .mobile-nav-item.danger-item {
            color: #dc3545 !important;
        }
        
        body {
            padding-top: 40px; /* Space for desktop */
            padding-bottom: 70px; /* Space for mobile bottom nav */
            background: linear-gradient(to bottom right, #ffffff, var(--blue-50), var(--blue-200));
            min-height: 100vh;
        }
        @media (min-width: 768px) {
            body {
                padding-bottom: 20px; /* Remove bottom space on desktop */
            }
        }

        /* Adjust DataTable styling to be premium */
        .datatable-glass {
            background: transparent !important;
        }
        .datatable-glass thead th {
            background: rgba(255, 255, 255, 0.6) !important;
            border-bottom: 2px solid rgba(13, 110, 253, 0.1) !important;
            color: var(--slate-700);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 1.5rem;
        }
        .datatable-glass tbody td {
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5) !important;
            color: var(--slate-700);
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }
        .datatable-glass tbody tr:hover td {
            background: rgba(255, 255, 255, 0.4) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.5rem !important;
            border: none !important;
            padding: 0.25rem 0.75rem !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--blue-600) !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3) !important;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Main Content wrapper -->
    <div class="container-fluid px-3 px-md-5 py-4 flex-grow-1">
        
        <!-- Desktop Tab Panel (Hidden on Mobile) -->
        <div class="desktop-tab-panel d-none d-md-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <a href="<?= base_url('buat-tiket') ?>" class="tab-btn <?= (current_url() == base_url('buat-tiket') || current_url() == base_url('ticket')) ? 'active' : '' ?>">
                    <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span> Form Pengajuan
                </a>
                <a href="<?= base_url('riwayat-tiket') ?>" class="tab-btn <?= current_url() == base_url('riwayat-tiket') ? 'active' : '' ?>">
                    <span class="material-symbols-outlined" style="font-size: 20px;">history</span> Riwayat
                </a>
                <?php if (isset($is_atasan) && $is_atasan): ?>
                <a href="<?= base_url('persetujuan') ?>" class="tab-btn <?= current_url() == base_url('persetujuan') ? 'active' : '' ?>">
                    <span class="material-symbols-outlined" style="font-size: 20px;">fact_check</span> Persetujuan
                    <?php if (isset($pending_approval_count) && $pending_approval_count > 0): ?>
                        <span class="badge badge-notification-tab"><?= $pending_approval_count ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
            </div>
            
            <div class="d-flex align-items-center gap-2 border-start border-2 border-white ps-3 ms-2">
                <a href="<?= base_url('akun') ?>" class="btn-top-profile">
                    <span class="material-symbols-outlined" style="font-size: 18px;">person</span> Profil
                </a>
                <a href="<?= base_url('logout') ?>" class="btn-top-logout">
                    <span class="material-symbols-outlined" style="font-size: 18px;">logout</span> Keluar
                </a>
            </div>
        </div>
