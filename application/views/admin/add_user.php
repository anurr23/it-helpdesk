<?php $this->load->view('admin/layout/header'); ?>



<div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm mx-auto" style="max-width: 800px;">
    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary" style="font-size: 28px;">person_add</span>
            <div>
                <h5 class="mb-0 fw-bold text-slate-800">Tambah Pengguna Baru</h5>
                <div class="text-slate-500" style="font-size: 13px;">Daftarkan akun pengguna baru ke dalam sistem.</div>
            </div>
        </div>
        <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-sm btn-light border d-flex align-items-center gap-1 shadow-sm rounded-pill px-3 py-2 fw-medium">
            <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span> Kembali
        </a>
    </div>

    <div class="p-4" style="background: rgba(255,255,255,0.4);">
        <?= form_open('admin/simpan-pengguna') ?>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-slate-700 fw-medium mb-1" style="font-size: 14px;">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 shadow-sm"><span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">person</span></span>
                        <input type="text" name="name" class="form-control form-control-lg bg-white border-0 shadow-sm" style="font-size: 14px;" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-slate-700 fw-medium mb-1" style="font-size: 14px;">Username <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 shadow-sm"><span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">alternate_email</span></span>
                        <input type="text" name="username" class="form-control form-control-lg bg-white border-0 shadow-sm" style="font-size: 14px;" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-slate-700 fw-medium mb-1" style="font-size: 14px;">No HP / WA</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 shadow-sm"><span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">call</span></span>
                        <input type="text" name="contact" class="form-control form-control-lg bg-white border-0 shadow-sm" style="font-size: 14px;">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-slate-700 fw-medium mb-1" style="font-size: 14px;">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 shadow-sm"><span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">mail</span></span>
                        <input type="email" name="email" class="form-control form-control-lg bg-white border-0 shadow-sm" style="font-size: 14px;">
                    </div>
                </div>

                <div class="col-12 mt-4 mb-2">
                    <h6 class="fw-bold text-slate-600 border-bottom pb-2">Hak Akses & Organisasi</h6>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-slate-700 fw-medium mb-1" style="font-size: 14px;">Role Akses</label>
                    <div class="input-group-select2">
                        <span class="input-group-text"><span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">admin_panel_settings</span></span>
                        <select name="role" class="form-select select2-glass" style="font-size: 14px;" required>
                            <option value="user" selected>User Standar</option>
                            <option value="admin">Admin IT</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-slate-700 fw-medium mb-1" style="font-size: 14px;">Departemen</label>
                    <div class="input-group-select2">
                        <span class="input-group-text"><span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">domain</span></span>
                        <select name="dept" class="form-select select2-glass" style="font-size: 14px;">
                            <option value="">- Pilih Departemen -</option>
                            <?php foreach($departments as $d): ?>
                                <option value="<?= $d->id ?>"><?= htmlspecialchars($d->dept_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mt-2 bg-white p-3 rounded-3 shadow-sm">
                        <label class="custom-checkbox-wrapper d-flex align-items-start gap-3 w-100 m-0">
                            <input type="checkbox" name="atasan" value="T" id="flexSwitchAtasan" class="custom-checkbox-input">
                            <div class="custom-checkbox-box mt-1 flex-shrink-0"></div>
                            <div>
                                <span class="custom-checkbox-label d-block fw-bold text-slate-800" style="font-size: 14px;">Tandai sebagai Approver / Atasan</span>
                                <div class="text-slate-500 mt-1" style="font-size: 12px; font-weight: normal; color: var(--slate-500);">Aktifkan ini jika pengguna memiliki hak untuk menyetujui tiket pengajuan dari user lain di departemennya.</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="col-12 mt-4 mb-2">
                    <h6 class="fw-bold text-slate-600 border-bottom pb-2">Keamanan</h6>
                </div>

                <div class="col-12">
                    <label class="form-label text-slate-700 fw-medium mb-1" style="font-size: 14px;">Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 shadow-sm"><span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">lock</span></span>
                        <input type="text" name="password" class="form-control form-control-lg bg-white border-0 shadow-sm" style="font-size: 14px;" placeholder="Biarkan kosong untuk default: sama dengan username">
                    </div>
                </div>
            </div>

            <div class="mt-5 d-flex justify-content-end gap-3">
                <a href="<?= base_url('admin/pengguna') ?>" class="btn btn-light rounded-pill px-4 shadow-sm fw-medium d-flex align-items-center">Batal</a>
                <button type="submit" class="btn btn-primary text-white rounded-pill px-5 shadow fw-medium d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span> Simpan User Baru
                </button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<?php $this->load->view('admin/layout/footer'); ?>
