<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lengkapi Profil Anda</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Google Fonts & Material Symbols -->
    <link href="<?= base_url('assets/css/material-symbols.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/inter.css') ?>" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <!-- Select2 CSS -->
    <link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

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
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">

    <main class="w-100 mx-auto px-3" style="max-width: 672px;">
        <div class="glass-card position-relative overflow-hidden">
            <!-- Header Area -->
            <div class="px-4 py-4 border-bottom position-relative z-1 d-flex gap-3 align-items-center" style="border-color: rgba(255,255,255,0.6) !important;">
                <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: var(--blue-100); border: 1px solid var(--blue-200); border-radius: 0.5rem; flex-shrink: 0;">
                    <span class="material-symbols-outlined" style="color: var(--blue-600); font-variation-settings: 'FILL' 1; font-size: 28px;">
                        manage_accounts
                    </span>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-slate-900 mb-1">Profil Pengguna</h1>
                    <?php if($is_default_password || $is_profile_incomplete): ?>
                        <p class="text-danger mb-0 fw-medium" style="font-size: 13px;">Anda wajib melengkapi data profil dan mengganti password default sebelum dapat melanjutkan.</p>
                    <?php else: ?>
                        <p class="text-slate-600 mb-0" style="font-size: 13px;">Perbarui informasi pribadi dan departemen Anda.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="px-4 py-4 position-relative z-1">
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger" style="font-size: 14px; border-radius: 0.5rem; background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.2); color: #dc3545;">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success" style="font-size: 14px; border-radius: 0.5rem; background: rgba(25,135,84,0.1); border: 1px solid rgba(25,135,84,0.2); color: #198754;">
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php echo form_open('akun/update', ['class' => 'd-flex flex-column gap-3']); ?>

                    <div class="row g-3">
                        <div class="col-md-6 d-flex flex-column gap-1">
                            <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Username</label>
                            <div class="input-icon-wrapper">
                                <span class="material-symbols-outlined">badge</span>
                                <input type="text" class="form-control-glass w-100" style="background: rgba(255,255,255,0.3); color: var(--slate-500); cursor: not-allowed;" value="<?= htmlspecialchars($user->username) ?>" readonly disabled>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex flex-column gap-1">
                            <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-icon-wrapper">
                                <span class="material-symbols-outlined">person</span>
                                <input type="text" name="name" class="form-control-glass w-100" value="<?= htmlspecialchars($user->name) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 d-flex flex-column gap-1">
                            <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Password Baru <?= $is_default_password ? '<span class="text-danger">* (Wajib diganti)</span>' : '(Opsional)' ?></label>
                            <div class="input-icon-wrapper">
                                <span class="material-symbols-outlined">lock</span>
                                <input type="password" name="password" id="password" class="form-control-glass w-100" <?= $is_default_password ? 'required' : '' ?> placeholder="Ketik password baru">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column gap-1">
                            <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Ulangi Password <?= $is_default_password ? '<span class="text-danger">*</span>' : '' ?></label>
                            <div class="input-icon-wrapper">
                                <span class="material-symbols-outlined">lock_reset</span>
                                <input type="password" name="password_confirm" id="password_confirm" class="form-control-glass w-100" <?= $is_default_password ? 'required' : '' ?> placeholder="Ketik ulang password">
                            </div>
                            <small id="passwordMatchError" class="text-danger d-none" style="font-size: 11px;">Password tidak cocok!</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 d-flex flex-column gap-1">
                            <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Email (Opsional)</label>
                            <div class="input-icon-wrapper">
                                <span class="material-symbols-outlined">mail</span>
                                <input type="email" name="email" class="form-control-glass w-100" value="<?= htmlspecialchars($user->email ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column gap-1">
                            <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">No. HP / WA (Opsional)</label>
                            <div class="input-icon-wrapper">
                                <span class="material-symbols-outlined">call</span>
                                <input type="text" name="contact" class="form-control-glass w-100" value="<?= htmlspecialchars($user->contact ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-1">
                        <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Departemen <span class="text-danger">*</span></label>
                        <select name="dept" class="form-select select2-glass" required>
                            <option value="" disabled <?= empty($user->dept) ? 'selected' : '' ?>>- Pilih Departemen -</option>
                            <?php foreach($departments as $d): ?>
                                <option value="<?= $d->id ?>" <?= (isset($user->dept) && $user->dept == $d->id) ? 'selected' : '' ?>><?= htmlspecialchars($d->dept_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex flex-column gap-1 mb-2">
                        <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Atasan (Approver) (Opsional)</label>
                        <select name="approver_id" class="form-select select2-glass">
                            <option value="" <?= empty($user->approver_id) ? 'selected' : '' ?>>- Pilih Atasan Anda -</option>
                            <?php foreach($atasans as $a): ?>
                                <option value="<?= $a->id ?>" <?= (isset($user->approver_id) && $user->approver_id == $a->id) ? 'selected' : '' ?>><?= htmlspecialchars($a->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text mt-1 text-slate-500" style="font-size: 11px;">
                            <span class="material-symbols-outlined" style="font-size: 12px; vertical-align: middle;">info</span> Atasan ini yang akan menerima dan menyetujui pengajuan tiket IT Anda.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-1" style="border-color: rgba(255,255,255,0.6) !important;">
                        <?php if(!$is_default_password && !$is_profile_incomplete): ?>
                            <a href="<?= $user->role == 'admin' ? base_url('admin/tiket') : base_url('buat-tiket') ?>" class="btn btn-outline-secondary d-flex align-items-center gap-1" style="font-size: 14px; border-radius: 0.5rem; background: rgba(255,255,255,0.5);">
                                <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span> Kembali
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger d-flex align-items-center gap-1" style="font-size: 14px; border-radius: 0.5rem; background: rgba(255,255,255,0.5);">
                                <span class="material-symbols-outlined" style="font-size: 18px;">logout</span> Keluar
                            </a>
                        <?php endif; ?>
                        
                        <button type="submit" class="btn-primary-custom px-4 d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 18px;">save</span> Simpan Profil
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <!-- Bootstrap Bundle -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- Select2 JS -->
    <script src="<?= base_url('assets/js/select2.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            // Initialize Departemen Select2
            $('select[name="dept"]').select2({
                width: '100%',
                placeholder: "- Pilih Departemen -"
            });

            // Data Atasan from PHP
            const atasans = <?php echo json_encode($atasans); ?>;
            const currentApproverId = '<?= isset($user->approver_id) ? $user->approver_id : '' ?>';
            const $atasanSelect = $('select[name="approver_id"]');

            function populateAtasan(deptId) {
                // Pertahankan pilihan jika masih valid
                let oldVal = $atasanSelect.val() || currentApproverId;
                
                $atasanSelect.empty();
                $atasanSelect.append('<option value="" disabled selected>- Pilih Atasan Anda -</option>');
                
                if (deptId) {
                    let filtered = atasans.filter(function(a) { return a.dept == deptId; });
                    filtered.forEach(function(a) {
                        let selected = (a.id == oldVal) ? 'selected' : '';
                        $atasanSelect.append('<option value="' + a.id + '" ' + selected + '>' + a.name + '</option>');
                    });
                }
                
                // Initialize Select2 with minimumInputLength
                if ($atasanSelect.hasClass("select2-hidden-accessible")) {
                    $atasanSelect.select2('destroy');
                }
                $atasanSelect.select2({
                    width: '100%',
                    minimumInputLength: 3,
                    language: {
                        inputTooShort: function(args) {
                            var remainingChars = args.minimum - args.input.length;
                            return "Ketik " + remainingChars + " karakter lagi untuk mencari atasan";
                        },
                        noResults: function() {
                            return "Atasan tidak ditemukan";
                        }
                    }
                });
            }

            // Listen for Dept change
            $('select[name="dept"]').on('change', function() {
                populateAtasan($(this).val());
            });

            // Init on load
            populateAtasan($('select[name="dept"]').val());

            // Password matching validation
            $('#password, #password_confirm').on('input', function () {
                var pwd = $('#password').val();
                var confirmPwd = $('#password_confirm').val();
                
                if (pwd !== '' && confirmPwd !== '') {
                    if (pwd === confirmPwd) {
                        $('#passwordMatchError').addClass('d-none');
                        $('#password_confirm').css('border-color', '#198754');
                    } else {
                        $('#passwordMatchError').removeClass('d-none');
                        $('#password_confirm').css('border-color', '#dc3545');
                    }
                } else {
                    $('#passwordMatchError').addClass('d-none');
                    $('#password_confirm').css('border-color', '');
                }
            });

            // Prevent form submit if passwords don't match
            $('form').on('submit', function(e) {
                var pwd = $('#password').val();
                var confirmPwd = $('#password_confirm').val();
                
                if (pwd !== '' && pwd !== confirmPwd) {
                    e.preventDefault();
                    $('#password_confirm').focus();
                    $('#passwordMatchError').removeClass('d-none');
                    $('#password_confirm').css('border-color', '#dc3545');
                }
            });
        });
    </script>
</body>
</html>
