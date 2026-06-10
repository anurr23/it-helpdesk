<?php $this->load->view('admin/layout/header'); ?>

<!-- Tab Content: Users Management -->
<div class="glass-card w-100 overflow-hidden d-flex flex-column shadow-sm">
    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.7) !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary">group</span>
            <h5 class="mb-0 fw-bold text-slate-800">Manajemen Pengguna</h5>
        </div>
        <a href="<?= base_url('admin/tambah-pengguna') ?>" class="btn btn-sm btn-primary d-flex align-items-center gap-1 shadow-sm rounded-pill px-3">
            <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Tambah User
        </a>
    </div>

    <div class="p-0 table-responsive">
        <table class="table table-hover table-borderless align-middle mb-0 datatable-glass" style="width:100%">
            <thead style="background: rgba(255,255,255,0.4);">
                <tr>
                    <th class="text-slate-500 fw-semibold px-4 py-3">Nama & Username</th>
                    <th class="text-slate-500 fw-semibold px-4 py-3">Role</th>
                    <th class="text-slate-500 fw-semibold px-4 py-3">Departemen</th>
                    <th class="text-slate-500 fw-semibold text-end px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <?php 
                                $initial = strtoupper(substr($u->name, 0, 1));
                                $colors = ['primary', 'success', 'danger', 'warning', 'info', 'secondary'];
                                $color = $colors[crc32($u->name) % count($colors)];
                            ?>
                            <div class="bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 42px; height: 42px; font-size: 18px;">
                                <?= $initial ?>
                            </div>
                            <div>
                                <div class="fw-bold text-slate-800" style="font-size: 15px;"><?= htmlspecialchars($u->name) ?></div>
                                <div class="text-slate-500 d-flex align-items-center gap-1" style="font-size: 12px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">alternate_email</span>
                                    <?= htmlspecialchars($u->username) ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="d-flex flex-column gap-1 align-items-start">
                            <span class="badge bg-<?= $u->role == 'admin' ? 'danger' : 'primary' ?> bg-opacity-10 text-<?= $u->role == 'admin' ? 'danger' : 'primary' ?> px-2 py-1" style="border: 1px solid rgba(var(--bs-<?= $u->role == 'admin' ? 'danger' : 'primary' ?>-rgb), 0.2);">
                                <?= strtoupper($u->role) ?>
                            </span>
                            <?php if($u->atasan == 'T'): ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1" style="border: 1px solid rgba(var(--bs-warning-rgb), 0.3);">
                                    <span class="material-symbols-outlined align-middle me-1" style="font-size: 12px;">star</span>Approver
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-700 fw-medium">
                        <?php if(!empty($u->dept_name)): ?>
                            <div class="d-flex align-items-center gap-1">
                                <span class="material-symbols-outlined text-slate-400" style="font-size: 16px;">domain</span>
                                <?= htmlspecialchars($u->dept_name) ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted fst-italic">- Tidak ada -</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end px-4 py-3">
                        <a href="<?= base_url('admin/edit-pengguna/' . $u->id) ?>" class="btn btn-sm btn-soft-primary d-inline-flex align-items-center gap-1 px-3 me-1">
                            <span class="material-symbols-outlined" style="font-size: 16px;">edit_document</span> Edit
                        </a>
                        <a href="<?= base_url('admin/hapus-pengguna/' . $u->id) ?>" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3" onclick="return confirm('Yakin ingin menghapus pengguna ini? Semua data terkait mungkin ikut terhapus atau menyebabkan error jika ada histori.')">
                            <span class="material-symbols-outlined" style="font-size: 16px;">delete</span> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('admin/layout/footer'); ?>
