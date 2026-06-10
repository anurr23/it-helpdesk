<?php $this->load->view('admin/layout/header'); ?>

<!-- Tab Content: Profile -->
<div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm" style="max-width: 672px; margin: 0 auto;">
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

            <div class="d-flex flex-column gap-1">
                <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Password Baru <?= $is_default_password ? '<span class="text-danger">* (Wajib diganti)</span>' : '(Opsional)' ?></label>
                <div class="input-icon-wrapper">
                    <span class="material-symbols-outlined">lock</span>
                    <input type="password" name="password" class="form-control-glass w-100" <?= $is_default_password ? 'required' : '' ?> placeholder="Masukkan password baru">
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
                <label class="form-label text-slate-700 fw-semibold mb-0" style="font-size: 12px;">Atasan (Approver) <span class="text-danger">*</span></label>
                <select name="approver_id" class="form-select select2-glass" required>
                    <option value="" disabled <?= empty($user->approver_id) ? 'selected' : '' ?>>- Pilih Atasan Anda -</option>
                    <?php foreach($atasans as $a): ?>
                        <option value="<?= $a->id ?>" <?= (isset($user->approver_id) && $user->approver_id == $a->id) ? 'selected' : '' ?>><?= htmlspecialchars($a->name) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text mt-1 text-slate-500" style="font-size: 11px;">
                    <span class="material-symbols-outlined" style="font-size: 12px; vertical-align: middle;">info</span> Atasan ini yang akan menerima dan menyetujui pengajuan tiket IT Anda.
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center pt-3 border-top mt-1" style="border-color: rgba(255,255,255,0.6) !important;">
                <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span> Simpan Profil
                </button>
            </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php $this->load->view('admin/layout/footer'); ?>

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
    });
</script>
