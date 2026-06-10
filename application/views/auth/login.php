<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IT Helpdesk - Login</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= base_url('assets/css/material-symbols.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/inter.css') ?>" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="d-flex align-items-center justify-content-center">

    <div class="glass-card" style="width: 100%; max-width: 440px; margin: 1rem;">
        
        <!-- Header Section -->
        <div class="px-4 pt-4 pb-3 text-center d-flex flex-column align-items-center">
            <div class="d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background-color: var(--blue-100); border: 1px solid var(--blue-200); border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="color: var(--blue-600); font-variation-settings: 'FILL' 1; font-size: 28px;">
                    support_agent
                </span>
            </div>
            <h1 class="h4 fw-bold text-slate-900 mb-1">IT Helpdesk</h1>
            <p class="text-slate-600 mb-0" style="font-size: 14px;">Sign in to IT Helpdesk to continue.</p>
        </div>

        <!-- Form Section -->
        <div class="px-4 pb-4">
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert" style="font-size: 14px; border-radius: 0.5rem; background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.2); color: #dc3545;">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success" role="alert" style="font-size: 14px; border-radius: 0.5rem; background: rgba(25,135,84,0.1); border: 1px solid rgba(25,135,84,0.2); color: #198754;">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php echo form_open('login', ['class' => 'd-flex flex-column gap-3']); ?>
                
                <!-- Username Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="username" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Username</label>
                    <div class="input-icon-wrapper">
                        <span class="material-symbols-outlined">person</span>
                        <input type="text" name="username" id="username" class="form-control-glass w-100" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Password</label>
                        <a href="<?= base_url('lupa-password') ?>" class="text-primary-custom text-decoration-none" style="font-size: 14px;">Lupa Password?</a>
                    </div>
                    <div class="input-icon-wrapper">
                        <span class="material-symbols-outlined">lock</span>
                        <input type="password" name="password" id="password" class="form-control-glass w-100" placeholder="••••••••" required>
                        <!-- For simplicity, the visibility toggle script can be added later if needed -->
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check mt-2 d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-slate-700" for="remember" style="font-size: 14px; cursor: pointer;">
                        Remember me for 30 days
                    </label>
                </div>

                <!-- Sign In Button -->
                <button type="submit" class="btn-primary-custom w-100 mt-2">
                    Sign In
                    <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
                </button>

            <?php echo form_close(); ?>
        </div>

        <!-- Footer Note -->
        <div class="px-4 py-3 text-center border-top" style="background-color: rgba(255,255,255,0.4); border-color: var(--slate-200);">
            <p class="text-slate-600 mb-0" style="font-size: 14px;">
                Belum punya akun? <a href="<?= base_url('daftar') ?>" class="text-primary-custom fw-semibold text-decoration-none">Daftar di sini</a>
            </p>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
