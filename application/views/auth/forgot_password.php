<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IT Helpdesk - Lupa Password</title>
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
                    lock_reset
                </span>
            </div>
            <h1 class="h4 fw-bold text-slate-900 mb-1">Lupa Password?</h1>
            <p class="text-slate-600 mb-0" style="font-size: 14px;">Masukkan username Anda untuk memulai reset password.</p>
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

            <?php echo form_open('lupa-password', ['class' => 'd-flex flex-column gap-3', 'id' => 'forgot-form']); ?>
                
                <!-- Username Input -->
                <div class="d-flex flex-column gap-1">
                    <label for="username" class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Username</label>
                    <div class="input-icon-wrapper" id="username-wrapper">
                        <span class="material-symbols-outlined">person</span>
                        <input type="text" name="username" id="username" class="form-control-glass w-100" placeholder="Masukkan username" required autofocus autocomplete="username">
                    </div>
                    <div id="username-feedback" class="d-none align-items-center gap-1 mt-1" style="font-size: 12px;" aria-live="polite"></div>
                </div>

                <!-- Recovery Options -->
                <div id="recovery-panel" class="recovery-panel">
                    <div id="recovery-actions" class="d-none flex-column gap-2">
                        <p class="text-slate-600 mb-0" style="font-size: 13px;">Pilih cara menerima link reset password:</p>
                        <button type="submit" name="method" value="email" id="btn-recovery-email" class="recovery-action-btn btn-recovery-email d-none">
                            <span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
                            Kirim Link via Email
                        </button>
                        <button type="submit" name="method" value="wa" id="btn-recovery-wa" class="recovery-action-btn btn-recovery-wa d-none">
                            <span class="material-symbols-outlined" style="font-size: 20px;">chat</span>
                            Kirim Link via WhatsApp
                        </button>
                    </div>

                    <div id="recovery-it-warning" class="recovery-it-alert d-none alert mb-0" role="alert" style="font-size: 13px; border-radius: 0.5rem; background: rgba(255, 193, 7, 0.12); border: 1px solid rgba(255, 193, 7, 0.35); color: #856404;">
                        <div class="d-flex align-items-start gap-2">
                            <span class="material-symbols-outlined" style="font-size: 20px; flex-shrink: 0;">support_agent</span>
                            <div>
                                <strong>Akun ditemukan, namun belum ada kontak terdaftar.</strong>
                                <p class="mb-0 mt-1">Email dan nomor HP pada akun ini masih kosong. Silakan hubungi Administrator Anda untuk bantuan reset password.</p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php echo form_close(); ?>
        </div>

        <!-- Footer Note -->
        <div class="px-4 py-3 text-center border-top" style="background-color: rgba(255,255,255,0.4); border-color: var(--slate-200);">
            <p class="text-slate-600 mb-0" style="font-size: 14px;">
                Ingat password Anda? <a href="<?= base_url('login') ?>" class="text-primary-custom fw-semibold text-decoration-none">Kembali ke Login</a>
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
        var recoveryPanel = document.getElementById('recovery-panel');
        var recoveryActions = document.getElementById('recovery-actions');
        var recoveryItWarning = document.getElementById('recovery-it-warning');
        var btnRecoveryEmail = document.getElementById('btn-recovery-email');
        var btnRecoveryWa = document.getElementById('btn-recovery-wa');
        var forgotForm = document.getElementById('forgot-form');
        var checkUrl = <?= json_encode(base_url('lupa-password/cek-username')) ?>;
        var debounceTimer = null;
        var requestId = 0;
        var usernameState = 'idle';

        function setUsernameInputState(type) {
            usernameInput.classList.remove('is-invalid', 'is-valid');
            usernameWrapper.classList.remove('is-invalid', 'is-valid');

            if (type === 'not_found' || type === 'invalid') {
                usernameInput.classList.add('is-invalid');
                usernameWrapper.classList.add('is-invalid');
            } else if (type === 'found') {
                usernameInput.classList.add('is-valid');
                usernameWrapper.classList.add('is-valid');
            }
        }

        function setUsernameFeedback(type, message) {
            var styles = {
                checking: { color: '#64748b', icon: 'progress_activity' },
                found: { color: '#198754', icon: 'check_circle' },
                invalid: { color: '#dc3545', icon: 'error' },
                not_found: { color: '#dc3545', icon: 'cancel' },
                no_contact: { color: '#856404', icon: 'warning' }
            };
            var style = styles[type] || styles.invalid;

            setUsernameInputState(type === 'no_contact' ? 'found' : type);

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

        function hideRecoveryPanel() {
            recoveryPanel.classList.remove('is-visible');
            recoveryActions.classList.add('d-none');
            recoveryActions.classList.remove('d-flex');
            recoveryItWarning.classList.add('d-none');
            btnRecoveryEmail.classList.add('d-none');
            btnRecoveryWa.classList.add('d-none');
        }

        function showRecoveryPanel(data) {
            recoveryPanel.classList.add('is-visible');

            if (data.can_reset) {
                recoveryActions.classList.remove('d-none');
                recoveryActions.classList.add('d-flex');
                recoveryItWarning.classList.add('d-none');

                if (data.has_email) {
                    btnRecoveryEmail.classList.remove('d-none');
                    btnRecoveryEmail.style.animation = 'none';
                    btnRecoveryEmail.offsetHeight;
                    btnRecoveryEmail.style.animation = '';
                } else {
                    btnRecoveryEmail.classList.add('d-none');
                }

                if (data.has_contact) {
                    btnRecoveryWa.classList.remove('d-none');
                    btnRecoveryWa.style.animation = 'none';
                    btnRecoveryWa.offsetHeight;
                    btnRecoveryWa.style.animation = '';
                } else {
                    btnRecoveryWa.classList.add('d-none');
                }
            } else {
                recoveryActions.classList.add('d-none');
                recoveryActions.classList.remove('d-flex');
                recoveryItWarning.classList.remove('d-none');
                recoveryItWarning.style.animation = 'none';
                recoveryItWarning.offsetHeight;
                recoveryItWarning.style.animation = '';
            }
        }

        function checkUsernameForReset() {
            var username = usernameInput.value.trim();

            if (username === '') {
                usernameState = 'idle';
                hideUsernameFeedback();
                hideRecoveryPanel();
                return;
            }

            if (username.length < 3) {
                usernameState = 'invalid';
                setUsernameFeedback('invalid', 'Username minimal 3 karakter.');
                hideRecoveryPanel();
                return;
            }

            usernameState = 'checking';
            setUsernameFeedback('checking', 'Memeriksa username...');
            hideRecoveryPanel();

            var currentRequest = ++requestId;

            fetch(checkUrl + '?username=' + encodeURIComponent(username), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (currentRequest !== requestId) return;
                    if (usernameInput.value.trim() !== username) return;

                    if (!data.found) {
                        usernameState = 'not_found';
                        setUsernameFeedback('not_found', data.message);
                        hideRecoveryPanel();
                        return;
                    }

                    usernameState = data.can_reset ? 'found' : 'no_contact';
                    setUsernameFeedback(
                        data.can_reset ? 'found' : 'no_contact',
                        data.message
                    );
                    showRecoveryPanel(data);
                })
                .catch(function () {
                    if (currentRequest !== requestId) return;
                    usernameState = 'invalid';
                    setUsernameFeedback('invalid', 'Gagal memeriksa username. Coba lagi.');
                    hideRecoveryPanel();
                });
        }

        usernameInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(checkUsernameForReset, 400);
        });

        usernameInput.addEventListener('blur', function () {
            clearTimeout(debounceTimer);
            checkUsernameForReset();
        });

        forgotForm.addEventListener('submit', function (event) {
            if (event.submitter && (event.submitter.name === 'method')) {
                if (usernameState !== 'found') {
                    event.preventDefault();
                    checkUsernameForReset();
                }
                return;
            }

            event.preventDefault();
        });
    })();
    </script>
    <style>
        @keyframes username-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</body>
</html>
