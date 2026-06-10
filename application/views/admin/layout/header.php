<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IT Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= base_url('assets/css/material-symbols.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/inter.css') ?>" rel="stylesheet">
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/fancybox.css') ?>" />
    <!-- Select2 CSS -->
    <link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link href="<?= base_url('assets/css/dataTables.bootstrap5.min.css') ?>" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link href="<?= base_url('assets/css/flatpickr.min.css') ?>" rel="stylesheet" />
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . time()) ?>">
    <style>
        /* Select2 Glassmorphism Overrides */
        .select2-container--default .select2-selection--single {
            background: rgba(255, 255, 255, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 0.5rem !important;
            height: 42px !important;
            display: flex;
            align-items: center;
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--slate-700) !important;
            padding-left: 1rem !important;
            line-height: normal !important;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 10px !important;
        }

        .select2-dropdown {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            overflow: hidden;
            padding: 4px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--slate-200) !important;
            border-radius: 0.25rem !important;
            background: rgba(255, 255, 255, 0.8) !important;
            font-size: 14px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            border-color: var(--primary) !important;
        }

        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            font-size: 14px;
            color: var(--slate-700);
            border-radius: 0.25rem;
            margin-bottom: 2px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: var(--blue-100) !important;
            color: var(--blue-700) !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: var(--primary) !important;
            color: white !important;
        }

        /* DataTables Glassmorphism Overrides */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background: rgba(255, 255, 255, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            color: var(--slate-700);
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary) !important;
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.page-item.active .page-link {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        }

        .dataTables_wrapper .dataTables_paginate .page-link {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: var(--slate-700);
            backdrop-filter: blur(5px);
            margin: 0 2px;
            border-radius: 0.375rem;
        }

        .dataTables_wrapper .dataTables_paginate .page-item:not(.active) .page-link:hover {
            background: rgba(255, 255, 255, 0.8);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 1rem 1.5rem;
            color: var(--slate-600);
            font-size: 14px;
        }

        .dataTables_wrapper .row {
            margin: 0;
            align-items: center;
        }
        
        .dataTables_wrapper .dataTables_paginate {
            padding: 1rem 1.5rem;
        }

        table.dataTable {
            border-collapse: collapse !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        table.dataTable thead th {
            border-bottom: 1px solid rgba(255,255,255,0.3) !important;
        }

        table.dataTable.table-hover > tbody > tr:hover > * {
            box-shadow: inset 0 0 0 9999px rgba(255, 255, 255, 0.2);
        }
        
        table.dataTable.table-borderless > tbody > tr > td,
        table.dataTable.table-borderless > tbody > tr > th {
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        table.dataTable > tbody > tr:last-child > td,
        table.dataTable > tbody > tr:last-child > th {
            border-bottom: none;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background: linear-gradient(135deg, #ffffff, #dbeafe);
        }
        /* Mobile nav bottom padding */
        body { padding-bottom: 64px; }
        @media (min-width: 768px) {
            body { padding-bottom: 0; }
        }
        .blob-bg {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            z-index: 0;
            mix-blend-mode: multiply;
        }
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
        }
        .nav-btn.active {
            background: linear-gradient(135deg, var(--primary), var(--blue-700)) !important;
            border-color: transparent !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3) !important;
        }
        .nav-btn.active .material-symbols-outlined {
            color: white !important;
        }
        .nav-btn:not(.active) {
            background: transparent;
            border: 1px solid transparent;
            color: #64748b;
        }
        .nav-btn:not(.active):hover {
            background: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body class="d-flex position-relative overflow-hidden">
    
    <!-- Decorative background elements -->
    <div class="blob-bg bg-primary position-fixed" style="top: -10%; left: 20%; width: 500px; height: 500px;"></div>
    <div class="blob-bg bg-info position-fixed" style="bottom: -20%; right: -10%; width: 600px; height: 600px;"></div>

    <!-- Setup IT Manager Check -->
    <?php
    $is_it_manager = false;
    $it_manager_count = 0;
    $CI =& get_instance();
    $CI->load->model('User_model');
    $CI->load->model('Ticket_model');
    $it_atasan = $CI->User_model->get_it_atasan();
    if ($it_atasan && $it_atasan->id == $this->session->userdata('user_id')) {
        $is_it_manager = true;
        $it_manager_count = count($CI->Ticket_model->get_tickets_pending_it_approval());
    }
    ?>

    <!-- Sidebar (Desktop) -->
    <aside class="glass-sidebar d-none d-md-flex flex-column vh-100 sticky-top z-3" style="width: 260px;">
        <div class="d-flex align-items-center px-4 py-3 gap-2 border-bottom" style="height: 72px; border-color: rgba(255,255,255,0.5) !important;">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; background: var(--primary);">
                <span class="material-symbols-outlined" style="font-size: 20px;">support_agent</span>
            </div>
            <h1 class="h6 mb-0 fw-bold text-primary-custom" style="letter-spacing: 0.5px;">IT Dashboard</h1>
        </div>
        
        <div class="flex-grow-1 px-3 py-4 d-flex flex-column gap-2 overflow-auto">
            <p class="text-uppercase fw-semibold text-slate-400 mb-2 px-2" style="font-size: 11px; letter-spacing: 1px;">Menu Utama</p>
            
            <a href="<?= base_url('admin/beranda') ?>" class="text-decoration-none btn d-flex align-items-center gap-2 px-3 py-2 text-start w-100 nav-btn <?= ($this->uri->segment(2) == 'beranda' || $this->uri->segment(2) == '') ? 'active' : '' ?>" style="border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="font-size: 20px;">dashboard</span>
                <span style="font-weight: 600; font-size: 14px;">Dashboard Utama</span>
            </a>
            <a href="<?= base_url('admin/tiket') ?>" class="text-decoration-none btn d-flex align-items-center gap-2 px-3 py-2 text-start w-100 nav-btn <?= ($this->uri->segment(2) == 'tiket') ? 'active' : '' ?>" style="border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="font-size: 20px;">monitoring</span>
                <span style="font-weight: 600; font-size: 14px;">Status Ticket</span>
            </a>
            <?php if($is_it_manager): ?>
            <a href="<?= base_url('admin/persetujuan') ?>" class="text-decoration-none btn d-flex align-items-center justify-content-between px-3 py-2 text-start w-100 nav-btn <?= ($this->uri->segment(2) == 'persetujuan') ? 'active' : '' ?>" style="border-radius: 0.5rem;">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 20px;">fact_check</span>
                    <span style="font-weight: 600; font-size: 14px;">Persetujuan IT</span>
                </div>
                <?php if($it_manager_count > 0): ?>
                    <span class="badge bg-danger rounded-pill" style="font-size: 11px;"><?= $it_manager_count ?></span>
                <?php endif; ?>
            </a>
            <?php endif; ?>
            <a href="<?= base_url('admin/riwayat') ?>" class="text-decoration-none btn d-flex align-items-center gap-2 px-3 py-2 text-start w-100 nav-btn <?= ($this->uri->segment(2) == 'riwayat') ? 'active' : '' ?>" style="border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="font-size: 20px;">history</span>
                <span style="font-weight: 600; font-size: 14px;">Riwayat Tiket</span>
            </a>
            <a href="<?= base_url('admin/pengguna') ?>" class="text-decoration-none btn d-flex align-items-center gap-2 px-3 py-2 text-start w-100 nav-btn <?= ($this->uri->segment(2) == 'pengguna') ? 'active' : '' ?>" style="border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="font-size: 20px;">group</span>
                <span style="font-weight: 600; font-size: 14px;">Manajemen User</span>
            </a>
            <a href="<?= base_url('admin/departemen') ?>" class="text-decoration-none btn d-flex align-items-center gap-2 px-3 py-2 text-start w-100 nav-btn <?= ($this->uri->segment(2) == 'departemen') ? 'active' : '' ?>" style="border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="font-size: 20px;">domain</span>
                <span style="font-weight: 600; font-size: 14px;">Master Dept</span>
            </a>
        </div>

        <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.5) !important;">
            <a href="<?= base_url('admin/profil') ?>" class="text-decoration-none d-flex align-items-center gap-2 p-2 rounded-3 mb-3 profile-btn" style="background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.8); transition: all 0.3s ease;">
                <div class="rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 38px; height: 38px; font-size: 16px; background: var(--primary); color: white; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);">
                    <?= strtoupper(substr($this->session->userdata('name'), 0, 1)) ?>
                </div>
                <div class="d-flex flex-column overflow-hidden">
                    <span class="fw-bold text-truncate" style="font-size: 14px; color: var(--slate-900);"><?= htmlspecialchars($this->session->userdata('name')) ?></span>
                    <span style="color: var(--primary); font-size: 11px; font-weight: 600; letter-spacing: 0.3px;">Edit Profil</span>
                </div>
            </a>
            <style>
                .profile-btn:hover {
                    background: rgba(255,255,255,0.9) !important;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                    transform: translateY(-1px);
                }
            </style>
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger w-100 d-flex justify-content-center align-items-center gap-1" style="border-radius: 0.5rem; font-size: 14px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">logout</span> Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1 d-flex flex-column vh-100 overflow-auto position-relative z-1">
        
        <!-- Header Mobile -->
        <header class="d-md-none d-flex align-items-center justify-content-between px-3 py-3 border-bottom sticky-top z-3" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(10px);">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; background: var(--primary);">
                    <span class="material-symbols-outlined" style="font-size: 18px;">support_agent</span>
                </div>
                <h1 class="h6 mb-0 fw-bold text-primary-custom">IT Dashboard</h1>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <a href="<?= base_url('admin/profil') ?>" class="text-primary">
                    <span class="material-symbols-outlined">person</span>
                </a>
                <a href="<?= base_url('logout') ?>" class="text-danger">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            </div>
        </header>

        <div class="p-4 p-md-5">
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 border-0 shadow-sm rounded-3 mb-4" style="background: rgba(209, 231, 221, 0.9); backdrop-filter: blur(10px);">
                    <span class="material-symbols-outlined">check_circle</span>
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 border-0 shadow-sm rounded-3 mb-4" style="background: rgba(248, 215, 218, 0.9); backdrop-filter: blur(10px);">
                    <span class="material-symbols-outlined">error</span>
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>
