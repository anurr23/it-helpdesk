<?php $this->load->view('admin/layout/header'); ?>

<!-- Tab Content: Depts Management -->
<div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm">
    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-secondary">domain</span>
            <h5 class="mb-0 fw-bold text-slate-800">Master Departemen</h5>
        </div>
        <button class="btn btn-sm btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addDeptModal">
            <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Tambah Dept
        </button>
    </div>
    <div class="p-0 table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0 datatable-glass" style="width:100%">
            <thead style="background: rgba(255,255,255,0.4);">
                <tr>
                    <th class="text-slate-500 fw-semibold px-4 py-3">Nama Departemen</th>
                    <th class="text-slate-500 fw-semibold px-4 py-3">Total Pengguna</th>
                    <th class="text-slate-500 fw-semibold text-end px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($departments as $d): ?>
                <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <span class="material-symbols-outlined" style="font-size: 20px;">domain</span>
                            </div>
                            <div class="fw-bold text-slate-800" style="font-size: 15px;"><?= htmlspecialchars($d->dept_name) ?></div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">
                        <span class="badge bg-light text-slate-700 border px-3 py-2 rounded-pill shadow-sm"><span class="material-symbols-outlined align-middle me-1" style="font-size: 14px;">group</span> <?= $d->user_count ?> User</span>
                    </td>
                    <td class="text-end px-4 py-3">
                        <a href="javascript:void(0)" class="btn btn-sm btn-soft-primary d-inline-flex align-items-center me-1" onclick="editDept('<?= $d->id ?>', '<?= htmlspecialchars(addslashes($d->dept_name)) ?>')">
                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                        </a>
                        <a href="<?= base_url('admin/hapus-departemen/' . $d->id) ?>" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center" onclick="return confirm('Yakin ingin menghapus departemen ini? Semua user di dept ini akan dikosongkan departernennya.')">
                            <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Dept Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
            <?= form_open('admin/simpan-departemen') ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary-custom d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">add_business</span>
                        Tambah Dept
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label text-slate-700 fw-medium" style="font-size: 13px;">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="dept_name" class="form-control form-control-lg bg-light border-0" style="font-size: 14px;" placeholder="Contoh: Keuangan" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- Edit Dept Modal -->
<div class="modal fade" id="editDeptModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
            <?= form_open('', ['id' => 'editDeptForm']) ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary-custom d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-primary">edit_square</span>
                        Edit Dept
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label text-slate-700 fw-medium" style="font-size: 13px;">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="dept_name" id="edit_dept_name" class="form-control form-control-lg bg-light border-0" style="font-size: 14px;" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan</button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
function editDept(id, name) {
    document.getElementById('edit_dept_name').value = name;
    document.getElementById('editDeptForm').action = '<?= base_url("admin/update-departemen/") ?>' + id;
    $('#editDeptModal').modal('show');
}
</script>

<?php $this->load->view('admin/layout/footer'); ?>
