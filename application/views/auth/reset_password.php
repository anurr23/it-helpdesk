<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IT Helpdesk - Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= base_url('assets/css/material-symbols.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/inter.css') ?>" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 2rem 0;">

    <div class="glass-card" style="width: 100%; max-width: 440px; margin: 1rem;">
        
        <!-- Header Section -->
        <div class="px-4 pt-4 pb-3 text-center d-flex flex-column align-items-center">
            <div class="d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background-color: var(--blue-100); border: 1px solid var(--blue-200); border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="color: var(--blue-600); font-variation-settings: 'FILL' 1; font-size: 28px;">
                    password
                </span>
            </div>
            <h1 class="h4 fw-bold text-slate-900 mb-1">Reset Password</h1>
            <p class="text-slate-600 mb-0" style="font-size: 14px;">Masukkan password baru untuk akun Anda.</p>
        </div>

        <!-- Form Section -->
        <div class="px-4 pb-4">
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert" style="font-size: 14px; border-radius: 0.5rem; background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.2); color: #dc3545;">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php echo form_open('auth/proses_reset_password', ['class' => 'd-flex flex-column gap-3', 'id' => 'reset-form']); ?>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <!-- Password Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="password" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Password Baru</label>
                    <div class="input-icon-wrapper" id="password-wrapper">
                        <span class="material-symbols-outlined">lock</span>
                        <input type="password" name="password" id="password" class="form-control-glass w-100" placeholder="••••••••" required autofocus>
                    </div>
                </div>

                <!-- Confirm Password Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="password_confirm" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Ulangi Password Baru</label>
                    <div class="input-icon-wrapper" id="password-confirm-wrapper">
                        <span class="material-symbols-outlined">lock_reset</span>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control-glass w-100" placeholder="••••••••" required>
                    </div>
                    <div id="password-feedback" class="d-none align-items-center gap-1 mt-1" style="font-size: 12px;" aria-live="polite"></div>
                </div>

                <!-- Reset Button -->
                <button type="submit" class="btn-primary-custom w-100 mt-2" id="reset-submit">
                    Simpan Password
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                </button>

            <?php echo form_close(); ?>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
    (function () {
        var passwordInput = document.getElementById('password');
        var passwordConfirmInput = document.getElementById('password_confirm');
        var passwordConfirmWrapper = document.getElementById('password-confirm-wrapper');
        var passwordFeedback = document.getElementById('password-feedback');
        var resetSubmit = document.getElementById('reset-submit');
        var resetForm = document.getElementById('reset-form');
        var passwordState = 'idle';

        function checkPasswords() {
            var pwd = passwordInput.value;
            var confirmPwd = passwordConfirmInput.value;

            if (confirmPwd === '') {
                passwordConfirmInput.classList.remove('is-invalid', 'is-valid');
                passwordConfirmWrapper.classList.remove('is-invalid', 'is-valid');
                passwordFeedback.className = 'd-none';
                passwordState = 'idle';
                resetSubmit.disabled = false;
                return;
            }

            if (pwd !== confirmPwd) {
                passwordConfirmInput.classList.add('is-invalid');
                passwordConfirmWrapper.classList.add('is-invalid');
                passwordConfirmInput.classList.remove('is-valid');
                passwordConfirmWrapper.classList.remove('is-valid');
                passwordFeedback.className = 'd-flex align-items-center gap-1 mt-1 text-danger';
                passwordFeedback.style.color = '#dc3545';
                passwordFeedback.innerHTML = '<span class="material-symbols-outlined" style="font-size: 16px;">error</span><span>Password tidak sama</span>';
                passwordState = 'invalid';
                resetSubmit.disabled = true;
            } else {
                passwordConfirmInput.classList.remove('is-invalid');
                passwordConfirmWrapper.classList.remove('is-invalid');
                passwordConfirmInput.classList.add('is-valid');
                passwordConfirmWrapper.classList.add('is-valid');
                passwordFeedback.className = 'd-flex align-items-center gap-1 mt-1 text-success';
                passwordFeedback.style.color = '#198754';
                passwordFeedback.innerHTML = '<span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span><span>Password cocok</span>';
                passwordState = 'valid';
                resetSubmit.disabled = false;
            }
        }

        passwordInput.addEventListener('input', checkPasswords);
        passwordConfirmInput.addEventListener('input', checkPasswords);

        resetForm.addEventListener('submit', function (event) {
            if (passwordState === 'invalid' || passwordInput.value !== passwordConfirmInput.value) {
                event.preventDefault();
                passwordConfirmInput.focus();
            }
        });
    })();
    </script>
    <style>
        .input-icon-wrapper.is-invalid {
            border: 1px solid #dc3545 !important;
            background-color: rgba(220,53,69,0.05) !important;
        }
        
        .form-control-glass.is-invalid {
            color: #dc3545 !important;
        }
        
        .input-icon-wrapper.is-invalid .material-symbols-outlined {
            color: #dc3545 !important;
        }
    </style>
</body>
</html>
