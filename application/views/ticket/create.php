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
                            <div class="file-preview-box d-none flex-column align-items-center justify-content-center p-3" id="previewBox" style="border: 2px dashed var(--primary); border-radius: 0.5rem; background: rgba(255, 255, 255, 0.9); z-index: 20; position: relative;">
                                <div class="position-relative w-100" style="max-height: 250px; overflow: hidden; border-radius: 0.25rem;">
                                    <img id="imagePreview" src="" alt="Preview" class="w-100 h-100" style="object-fit: contain; max-height: 250px;">
                                </div>
                                <div class="d-flex align-items-center justify-content-between w-100 mt-3 pt-3 border-top">
                                    <div class="d-flex align-items-center gap-2" style="max-width: 70%;">
                                        <span class="material-symbols-outlined text-primary" style="font-size: 20px;">image</span>
                                        <span id="fileName" class="text-slate-900 fw-medium text-truncate" style="font-size: 14px;">filename.jpg</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 py-1 px-2" id="btnRemoveFile">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">delete</span> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="btn-primary-custom w-100 py-2">
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
