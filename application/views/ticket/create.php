<?php $this->load->view('ticket/layout/header'); ?>
<style>
    input[type="file"]::file-selector-button {
        display: none;
    }
    .file-upload-wrapper {
        position: relative;
        width: 100%;
    }
    .file-upload-input {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }
    .file-upload-box {
        border: 2px dashed var(--slate-400);
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(4px);
    }
    .file-upload-wrapper:hover .file-upload-box {
        background: rgba(255, 255, 255, 0.8);
        border-color: var(--primary);
    }
</style>

<main class="w-100 mx-auto" style="max-width: 672px;">
        
        <!-- Form Container -->
        <div class="glass-card position-relative overflow-hidden">
            <!-- Header Area -->
            <div class="px-4 py-4 border-bottom position-relative z-1" style="border-color: rgba(255,255,255,0.6) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h1 class="h4 fw-bold text-slate-900 mb-0">Form Pengajuan Bantuan IT</h1>
                </div>
                <p class="text-slate-600 mb-0" style="font-size: 14px;">Silakan lengkapi formulir di bawah ini dengan detail agar kami dapat segera membantu Anda.</p>
            </div>

            <!-- Form Content -->
            <div class="px-4 py-4 position-relative z-1">
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success" style="font-size: 14px; border-radius: 0.5rem; background: rgba(25,135,84,0.1); border: 1px solid rgba(25,135,84,0.2); color: #198754;">
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger" style="font-size: 14px; border-radius: 0.5rem; background: rgba(220,53,69,0.1); border: 1px solid rgba(220,53,69,0.2); color: #dc3545;">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php echo form_open_multipart('buat-tiket', ['class' => 'd-flex flex-column gap-4']); ?>
                    
                    <?php if(!$has_approver): ?>
                        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0" style="font-size: 13px; border-radius: 0.5rem; background: rgba(255,193,7,0.15); border: 1px solid rgba(255,193,7,0.3); color: #b47d00;">
                            <span class="material-symbols-outlined" style="font-size: 20px;">warning</span>
                            <div>
                                Anda belum memilih Atasan (Approver). Anda <strong>tidak dapat</strong> mengirim pengajuan ini sebelum melengkapi Profil Anda. 
                                <a href="<?= base_url('akun') ?>" class="fw-bold text-decoration-underline" style="color: #b47d00;">Lengkapi Sekarang</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Judul Permintaan -->
                    <div class="d-flex flex-column gap-1">
                        <label for="judul" class="form-label text-slate-900 fw-semibold mb-0" style="font-size: 12px;">Judul Permintaan</label>
                        <input type="text" name="judul" id="judul" class="form-control form-control-glass" placeholder="Misal: Lupa Password, Internet Mati, dll" required>
                    </div>

                    <!-- Deskripsi Masalah -->
                    <div class="d-flex flex-column gap-1">
                        <label for="deskripsi" class="form-label text-slate-900 fw-semibold mb-0" style="font-size: 12px;">Deskripsi Masalah</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control form-control-glass" placeholder="Jelaskan kendala yang Anda alami secara detail..." required></textarea>
                    </div>

                    <!-- Lampiran -->
                    <div class="d-flex flex-column gap-1">
                        <label class="form-label text-slate-900 fw-semibold mb-0" style="font-size: 12px;">Lampiran (Hanya Gambar)</label>
                        <div class="file-upload-wrapper" id="uploadWrapper">
                            <input type="file" name="lampiran" id="lampiran" class="file-upload-input" accept="image/*">
                            <div class="file-upload-box d-flex flex-column align-items-center justify-content-center" id="uploadBox">
                                <span class="material-symbols-outlined text-slate-400 mb-2" style="font-size: 32px;">cloud_upload</span>
                                <span class="fw-medium text-slate-900 mb-1" style="font-size: 18px;">Tarik dan lepas gambar di sini</span>
                                <span class="text-slate-600 mb-0" style="font-size: 14px;">atau klik untuk memilih file</span>
                                <span class="text-slate-400 mt-2" style="font-size: 12px; font-weight: 600; letter-spacing: 0.05em;">Maksimal ukuran file: 10MB (JPG, PNG, GIF)</span>
                            </div>
                            <!-- Preview Box (Hidden by default) -->
                            <div class="file-preview-box d-none flex-column position-relative w-100" id="previewBox" style="border-radius: 0.75rem; overflow: hidden; background: rgba(255, 255, 255, 0.8); border: 1px solid var(--slate-200); box-shadow: 0 4px 20px rgba(0,0,0,0.06); z-index: 20; transition: all 0.3s ease;">
                                <!-- Close Button Top Right -->
                                <button type="button" class="btn btn-danger rounded-circle position-absolute d-flex align-items-center justify-content-center shadow" id="btnRemoveFile" style="top: 12px; right: 12px; width: 32px; height: 32px; padding: 0; z-index: 30; border: 2px solid white; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                    <span class="material-symbols-outlined" style="font-size: 18px; font-weight: bold;">close</span>
                                </button>
                                
                                <!-- Image Area -->
                                <div class="w-100 d-flex justify-content-center align-items-center position-relative" style="height: 220px; background: radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(241,245,249,1) 100%);">
                                    <img id="imagePreview" src="" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.1)); padding: 1rem;">
                                </div>
                                
                                <!-- File Info Footer -->
                                <div class="d-flex align-items-center gap-3 p-3 border-top" style="background: rgba(255,255,255,0.95);">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: rgba(13, 110, 253, 0.1); color: var(--primary);">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">photo_library</span>
                                    </div>
                                    <div class="d-flex flex-column overflow-hidden flex-grow-1">
                                        <span id="fileName" class="text-slate-900 fw-bold text-truncate" style="font-size: 13px;">filename.jpg</span>
                                        <span class="text-slate-500" style="font-size: 11px;">File siap dilampirkan</span>
                                    </div>
                                    <span class="material-symbols-outlined text-success" style="font-size: 20px;">check_circle</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="btn-primary-custom w-100 py-2 <?= !$has_approver ? 'disabled' : '' ?>" <?= !$has_approver ? 'disabled' : '' ?> style="<?= !$has_approver ? 'opacity: 0.6; cursor: not-allowed;' : '' ?>">
                            <span class="material-symbols-outlined">send</span>
                            Kirim Permintaan
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>

    </main>

<?php $this->load->view('ticket/layout/footer'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('lampiran');
            const uploadBox = document.getElementById('uploadBox');
            const previewBox = document.getElementById('previewBox');
            const imagePreview = document.getElementById('imagePreview');
            const fileName = document.getElementById('fileName');
            const btnRemoveFile = document.getElementById('btnRemoveFile');

            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    // Validasi tipe file
                    if (!file.type.match('image.*')) {
                        alert('Hanya file gambar yang diperbolehkan!');
                        this.value = '';
                        return;
                    }
                    // Setup preview
                    fileName.textContent = file.name;
                    imagePreview.src = URL.createObjectURL(file);
                    
                    // Sembunyikan input asli dan box upload
                    fileInput.classList.add('d-none');
                    uploadBox.classList.add('d-none');
                    uploadBox.classList.remove('d-flex');
                    
                    // Tampilkan preview
                    previewBox.classList.remove('d-none');
                    previewBox.classList.add('d-flex');
                }
            });

            btnRemoveFile.addEventListener('click', function(e) {
                e.preventDefault();
                // Reset file input
                fileInput.value = '';
                
                // Hapus URL object untuk menghemat memori
                if(imagePreview.src) URL.revokeObjectURL(imagePreview.src);
                imagePreview.src = '';
                
                // Sembunyikan preview
                previewBox.classList.add('d-none');
                previewBox.classList.remove('d-flex');
                
                // Tampilkan kembali box upload
                uploadBox.classList.remove('d-none');
                uploadBox.classList.add('d-flex');
                fileInput.classList.remove('d-none');
            });
        });
    </script>
