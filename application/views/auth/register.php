<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IT Helpdesk - Daftar Akun</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= base_url('assets/css/material-symbols.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/inter.css') ?>" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 2rem 0;">

    <div class="glass-card" style="width: 100%; max-width: 480px; margin: 1rem;">
        
        <!-- Header Section -->
        <div class="px-4 pt-4 pb-3 text-center d-flex flex-column align-items-center">
            <div class="d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background-color: var(--blue-100); border: 1px solid var(--blue-200); border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="color: var(--blue-600); font-variation-settings: 'FILL' 1; font-size: 28px;">
                    person_add
                </span>
            </div>
            <h1 class="h4 fw-bold text-slate-900 mb-1">Daftar Akun Baru</h1>
            <p class="text-slate-600 mb-0" style="font-size: 14px;">Lengkapi data di bawah untuk bergabung.</p>
        </div>

        <!-- Form Section -->
        <div class="px-4 pb-4">
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert" style="font-size: 14px; border-radius: 0.5rem; background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.2); color: #dc3545;">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php 
                $err_user = $this->session->flashdata('error_username') ? 'is-invalid' : ''; 
                $old_n = htmlspecialchars($this->session->flashdata('old_name') ?? '');
                $old_u = htmlspecialchars($this->session->flashdata('old_username') ?? '');
                $old_e = htmlspecialchars($this->session->flashdata('old_email') ?? '');
            ?>

            <?php echo form_open('daftar', ['class' => 'd-flex flex-column gap-3', 'id' => 'register-form']); ?>
                
                <!-- Name Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="name" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Nama Lengkap</label>
                    <div class="input-icon-wrapper">
                        <span class="material-symbols-outlined">badge</span>
                        <input type="text" name="name" id="name" class="form-control-glass w-100" placeholder="Masukkan nama lengkap" value="<?= $old_n ?>" required autofocus>
                    </div>
                </div>

                <!-- Username Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="username" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Username</label>
                    <div class="input-icon-wrapper <?= $err_user ?>" id="username-wrapper">
                        <span class="material-symbols-outlined">person</span>
                        <input type="text" name="username" id="username" class="form-control-glass w-100 <?= $err_user ?>" placeholder="Masukkan username" value="<?= $old_u ?>" required autocomplete="username">
                    </div>
                    <div id="username-feedback" class="align-items-center gap-1 mt-1 <?= $err_user ? 'd-flex text-danger' : 'd-none' ?>" style="font-size: 12px; <?= $err_user ? 'color: #dc3545;' : '' ?>" aria-live="polite">
                        <?php if($err_user): ?>
                            <span class="material-symbols-outlined" style="font-size: 16px;">error</span><span>Username sudah terdaftar</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Email Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="email" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Alamat Email</label>
                    <div class="input-icon-wrapper">
                        <span class="material-symbols-outlined">mail</span>
                        <input type="email" name="email" id="email" class="form-control-glass w-100" placeholder="Masukkan alamat email" value="<?= $old_e ?>">
                    </div>
                </div>

                <!-- WhatsApp Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="contact" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">No WhatsApp</label>
                    <div class="input-icon-wrapper">
                        <span class="material-symbols-outlined">call</span>
                        <input type="tel" name="contact" id="contact" class="form-control-glass w-100" placeholder="Contoh: 08123456789" value="<?= htmlspecialchars($this->session->flashdata('old_contact') ?? '') ?>" pattern="^08[0-9]{8,11}$" title="Nomor WhatsApp harus diawali dengan 08 dan terdiri dari 10 hingga 13 digit angka" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 0 && this.value.substring(0,2) !== '08') { this.setCustomValidity('Harus diawali dengan 08'); } else { this.setCustomValidity(''); }">
                    </div>
                    <small class="text-muted" style="font-size: 11px;">* Wajib mengisi salah satu dari Email atau No WhatsApp</small>
                </div>

                <!-- Approver Checkbox -->
                <label class="custom-checkbox-wrapper mt-2 mb-2">
                    <input type="checkbox" name="atasan" id="atasan" value="1" class="custom-checkbox-input">
                    <div class="custom-checkbox-box"></div>
                    <span class="custom-checkbox-label">Apakah Anda sebagai Approver / Atasan?</span>
                </label>

                <!-- Password Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="password" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Password</label>
                    <div class="input-icon-wrapper" id="password-wrapper">
                        <span class="material-symbols-outlined">lock</span>
                        <input type="password" name="password" id="password" class="form-control-glass w-100" placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Confirm Password Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="password_confirm" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Ulangi Password</label>
                    <div class="input-icon-wrapper" id="password-confirm-wrapper">
                        <span class="material-symbols-outlined">lock_reset</span>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control-glass w-100" placeholder="••••••••" required>
                    </div>
                    <div id="password-feedback" class="d-none align-items-center gap-1 mt-1" style="font-size: 12px;" aria-live="polite"></div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn-primary-custom w-100 mt-3" id="register-submit">
                    Daftar Sekarang
                    <span class="material-symbols-outlined" style="font-size: 18px;">how_to_reg</span>
                </button>

            <?php echo form_close(); ?>
        </div>

        <!-- Footer Note -->
        <div class="px-4 py-3 text-center border-top" style="background-color: rgba(255,255,255,0.4); border-color: var(--slate-200);">
            <p class="text-slate-600 mb-0" style="font-size: 14px;">
                Sudah punya akun? <a href="<?= base_url('login') ?>" class="text-primary-custom fw-semibold text-decoration-none">Login di sini</a>
            </p>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
    (function () {
        var usernameInput = document.getElementById('username');
        var usernameWrapper = document.getElementById('username-wrapper');
        var usernameFeedback = document.getElementById('username-feedback');
        var registerForm = document.getElementById('register-form');
        var registerSubmit = document.getElementById('register-submit');
        var checkUrl = <?= json_encode(base_url('daftar/cek-username')) ?>;
        var debounceTimer = null;
        var requestId = 0;
        var usernameState = 'idle';
        var pendingSubmit = false;

        function setUsernameInputState(type) {
            usernameInput.classList.remove('is-invalid', 'is-valid');
            usernameWrapper.classList.remove('is-invalid', 'is-valid');

            if (type === 'taken' || type === 'invalid') {
                usernameInput.classList.add('is-invalid');
                usernameWrapper.classList.add('is-invalid');
            } else if (type === 'available') {
                usernameInput.classList.add('is-valid');
                usernameWrapper.classList.add('is-valid');
            }
        }

        function setUsernameFeedback(type, message) {
            var styles = {
                checking: { color: '#64748b', icon: 'progress_activity' },
                available: { color: '#198754', icon: 'check_circle' },
                invalid: { color: '#dc3545', icon: 'error' },
                taken: { color: '#dc3545', icon: 'cancel' }
            };
            var style = styles[type] || styles.invalid;

            setUsernameInputState(type);

            usernameFeedback.className = 'd-flex align-items-center gap-1 mt-1';
            usernameFeedback.style.color = style.color;
            usernameFeedback.innerHTML =
                '<span class="material-symbols-outlined" style="font-size: 16px;' +
                (type === 'checking' ? ' animation: username-spin 1s linear infinite;' : '') +
                '">' + style.icon + '</span><span>' + message + '</span>';
        }

        function hideUsernameFeedback() {
            usernameFeedback.className = 'd-none align-items-center gap-1 mt-1';
            usernameFeedback.textContent = '';
            setUsernameInputState('idle');
        }

        function updateSubmitState() {
            registerSubmit.disabled = usernameState === 'taken' || usernameState === 'checking' || usernameState === 'invalid' || passwordState === 'invalid';
        }

        var passwordInput = document.getElementById('password');
        var passwordConfirmInput = document.getElementById('password_confirm');
        var passwordConfirmWrapper = document.getElementById('password-confirm-wrapper');
        var passwordFeedback = document.getElementById('password-feedback');
        var passwordState = 'idle';

        function checkPasswords() {
            var pwd = passwordInput.value;
            var confirmPwd = passwordConfirmInput.value;

            if (confirmPwd === '') {
                passwordConfirmInput.classList.remove('is-invalid', 'is-valid');
                passwordConfirmWrapper.classList.remove('is-invalid', 'is-valid');
                passwordFeedback.className = 'd-none';
                passwordState = 'idle';
                updateSubmitState();
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
            } else {
                passwordConfirmInput.classList.remove('is-invalid');
                passwordConfirmWrapper.classList.remove('is-invalid');
                passwordConfirmInput.classList.add('is-valid');
                passwordConfirmWrapper.classList.add('is-valid');
                passwordFeedback.className = 'd-flex align-items-center gap-1 mt-1 text-success';
                passwordFeedback.style.color = '#198754';
                passwordFeedback.innerHTML = '<span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span><span>Password cocok</span>';
                passwordState = 'valid';
            }
            updateSubmitState();
        }

        passwordInput.addEventListener('input', checkPasswords);
        passwordConfirmInput.addEventListener('input', checkPasswords);

        function checkUsernameAvailability() {
            var username = usernameInput.value.trim();

            if (username === '') {
                usernameState = 'idle';
                hideUsernameFeedback();
                updateSubmitState();
                return;
            }

            if (username.length < 3) {
                usernameState = 'invalid';
                setUsernameFeedback('invalid', 'Username minimal 3 karakter.');
                updateSubmitState();
                return;
            }

            usernameState = 'checking';
            setUsernameFeedback('checking', 'Memeriksa ketersediaan username...');
            updateSubmitState();

            var currentRequest = ++requestId;

            fetch(checkUrl + '?username=' + encodeURIComponent(username), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (currentRequest !== requestId) return;
                    if (usernameInput.value.trim() !== username) return;

                    if (data.available) {
                        usernameState = 'available';
                        setUsernameFeedback('available', data.message);
                        if (pendingSubmit) {
                            pendingSubmit = false;
                            registerForm.submit();
                            return;
                        }
                    } else {
                        usernameState = 'taken';
                        setUsernameFeedback('taken', data.message);
                        pendingSubmit = false;
                    }
                    updateSubmitState();
                })
                .catch(function () {
                    if (currentRequest !== requestId) return;
                    usernameState = 'invalid';
                    pendingSubmit = false;
                    setUsernameFeedback('invalid', 'Gagal memeriksa username. Coba lagi.');
                    updateSubmitState();
                });
        }

        usernameInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(checkUsernameAvailability, 400);
        });

        usernameInput.addEventListener('blur', function () {
            clearTimeout(debounceTimer);
            checkUsernameAvailability();
        });

        registerForm.addEventListener('submit', function (event) {
            var username = usernameInput.value.trim();
            var emailVal = document.getElementById('email').value.trim();
            var contactVal = document.getElementById('contact').value.trim();

            if (emailVal === '' && contactVal === '') {
                event.preventDefault();
                showGlassToast('Wajib mengisi salah satu dari Alamat Email atau No WhatsApp!', 'error');
                document.getElementById('email').focus();
                return;
            }

            if (username.length < 3) {
                event.preventDefault();
                usernameState = 'invalid';
                setUsernameFeedback('invalid', 'Username minimal 3 karakter.');
                updateSubmitState();
                usernameInput.focus();
                return;
            }

            if (passwordState === 'invalid') {
                event.preventDefault();
                passwordConfirmInput.focus();
                return;
            }

            if (usernameState !== 'available') {
                event.preventDefault();
                if (usernameState === 'taken' || usernameState === 'invalid') {
                    setUsernameFeedback(usernameState, usernameState === 'taken'
                        ? 'Username tidak valid atau sudah digunakan.'
                        : 'Username minimal 3 karakter.');
                    usernameInput.focus();
                    return;
                }
                pendingSubmit = true;
                setUsernameFeedback('checking', 'Tunggu sebentar, username sedang diperiksa...');
                updateSubmitState();
                checkUsernameAvailability();
            }
        });

        function showGlassToast(message, type) {
            var existingToast = document.getElementById('glass-toast');
            if (existingToast) {
                existingToast.remove();
            }

            var toast = document.createElement('div');
            toast.id = 'glass-toast';
            toast.className = 'glass-toast ' + type;
            
            var iconName = type === 'error' ? 'error' : 'check_circle';
            
            toast.innerHTML = 
                '<div class="glass-toast-icon">' +
                    '<span class="material-symbols-outlined">' + iconName + '</span>' +
                '</div>' +
                '<div class="glass-toast-content">' + message + '</div>';
                
            document.body.appendChild(toast);
            
            // Trigger animation
            setTimeout(function() {
                toast.classList.add('show');
            }, 10);
            
            // Auto hide after 4s
            setTimeout(function() {
                toast.classList.remove('show');
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }, 4000);
        }
    })();
    </script>
    <style>
        @keyframes username-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
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
