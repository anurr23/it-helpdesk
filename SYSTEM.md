# Dokumentasi Detail Alur (Flow) Project IT Helpdesk

Dokumen ini merangkum secara lengkap dan terperinci mengenai alur kerja (flow) dari aplikasi IT Helpdesk yang telah dibangun, mencakup otentikasi, pembagian peran (role), alur pengajuan tiket, hingga proses persetujuan dan penyelesaian oleh tim IT. Aplikasi ini dibangun dengan basis **CodeIgniter 3**, menggunakan **PostgreSQL** dengan arsitektur UUID, serta mengusung antarmuka **Modern Glassmorphism**.

---

## 1. Sistem Otentikasi & Manajemen Pengguna (Role System)
Aplikasi membedakan akses dan menu berdasarkan profil pengguna yang login:
- **Pengguna Biasa (User)**: Hanya memiliki akses untuk membuat tiket, melihat riwayat tiket sendiri, dan detail tiketnya.
- **Atasan Departemen (Supervisor)**: Memiliki hak akses pengguna biasa, ditambah menu khusus **"Persetujuan"** untuk melihat dan menyetujui/menolak tiket yang diajukan oleh bawahannya (dalam departemen yang sama).
- **Staf IT (Admin)**: Memiliki akses ke halaman *Dashboard* Admin untuk melihat seluruh tiket yang sedang berjalan, riwayat seluruh tiket yang sudah selesai, manajemen pengguna, dan departemen.
- **Atasan IT (IT Manager)**: Memiliki hak akses Staf IT, ditambah menu eksklusif **"Persetujuan IT"** untuk meninjau tiket yang masuk sebelum diserahkan untuk dikerjakan oleh tim IT.

---

## 2. Alur Pengajuan Tiket (User Flow)
Ini adalah siklus awal dari proses Helpdesk.

1. **Form Pengajuan Bantuan (`ticket/create`)**
   - Pengguna (*User*) login dan diarahkan ke halaman utama (Form Pengajuan).
   - Pengguna mengisi **Deskripsi Masalah** secara detail.
   - Pengguna dapat menyertakan **Lampiran** (berupa gambar/foto keluhan) menggunakan sistem *drag-and-drop* yang modern.
   - Setelah di-submit, tiket baru dibuat di *database* PostgreSQL.
   - **Status Awal:** `pending` (Menunggu Persetujuan Atasan).

2. **Riwayat Tiket Pengguna (`ticket/history`)**
   - Pengguna dapat melihat daftar seluruh tiket yang pernah mereka ajukan.
   - Terdapat kolom **Tanggal, ID Tiket, Deskripsi, Lampiran, Status, dan Aksi**.
   - Pengguna dapat mengeklik tombol **Lihat** pada kolom Lampiran, yang akan menampilkan foto secara *pop-up* halus menggunakan integrasi *Fancybox*.
   - Setiap tiket memiliki lencana status (*Soft Badge*) yang modern sesuai kondisinya.

---

## 3. Alur Persetujuan Atasan Departemen (Approval Flow)
Agar permintaan IT terkontrol, setiap pengajuan harus melalui persetujuan atasan (*supervisor*) dari pemohon.

1. **Notifikasi & Daftar Tunggu (`ticket/approval_list`)**
   - Jika pengguna yang login berstatus sebagai "Atasan" (`is_atasan = true`), mereka akan melihat menu **Persetujuan** di *Tab Panel* (desktop) atau *Bottom Nav* (mobile). Terdapat *badge* angka merah jika ada tiket yang menunggu.
   - Atasan melihat daftar tiket dari bawahannya yang berstatus `pending`.
   
2. **Proses Validasi Atasan (`ticket/approve_guest`)**
   - Atasan membuka detail tiket dan mengecek keluhan.
   - **Disetujui (Approve):** Jika disetujui, status tiket berubah dari `pending` menjadi `pending_it` (Menunggu IT).
   - **Ditolak (Reject):** Jika ditolak, status tiket berubah menjadi `rejected` dan tiket berhenti di tahap ini.

---

## 4. Alur Persetujuan Manajer IT (IT Manager Flow)
Setelah tiket lolos dari atasan pemohon, tiket akan diverifikasi terlebih dahulu oleh Kepala IT sebelum dikerjakan oleh staf.

1. **Daftar Persetujuan IT (`admin/approval_it`)**
   - Menu ini **sangat eksklusif**, hanya muncul di sisi kiri (*sidebar*) panel Admin jika pengguna yang login adalah **Atasan IT** (dicocokkan berdasarkan departemen IT dan flag `atasan = 'T'`).
   - Atasan IT melihat daftar tiket yang memiliki status `pending_it`.

2. **Tindakan Atasan IT (`ticket/approve_it`)**
   - Atasan IT membuka detail tiket untuk memproses.
   - **Diterima:** Atasan IT memberikan persetujuan, dan status tiket akan berubah menjadi `in_progress` (Dikerjakan IT). Tiket kini resmi masuk antrean kerja teknisi IT.
   - **Ditolak:** Jika dirasa bukan ranah IT atau tidak valid, tiket ditolak dan berstatus `rejected`.

---

## 5. Alur Penyelesaian oleh Tim IT (Admin/Staff Flow)
Ini adalah eksekusi teknis dari tiket yang telah diverifikasi sepenuhnya.

1. **Dashboard Status Tiket Admin (`admin/dashboard`)**
   - Seluruh staf IT dapat melihat halaman pemantauan utama ini.
   - Halaman ini menampilkan semua tiket aktif yang harus dipantau. Tiket yang muncul di sini adalah tiket dengan status `pending_it` (hanya untuk dilihat statusnya) dan `in_progress` (untuk dikerjakan).
   
2. **Penyelesaian Tiket (`admin/ticket_detail`)**
   - Teknisi IT mengambil tindakan fisik/sistem untuk memperbaiki masalah yang dilaporkan pengguna.
   - Jika sudah selesai, teknisi mengklik tombol **Selesaikan** (Mark as Resolved).
   - **Status Akhir:** `resolved` (Selesai).

3. **Riwayat Penyelesaian (`admin/history`)**
   - Seluruh tiket yang berstatus `resolved` atau `rejected` akan dipindahkan visualnya dari halaman *Dashboard* aktif ke halaman **Riwayat Tiket Admin**.
   - Admin dapat melakukan pencarian/filter di tabel menggunakan fitur pencarian khusus (*footer search filter* pada *DataTables*).

---

## 6. Arsitektur Antarmuka (UI/UX Design Stack)
Keseluruhan aplikasi dibangun dengan prinsip estetika modern dan responsif:
- **Tema Glassmorphism**: Penggunaan *background* semi-transparan (`rgba`), efek *backdrop-filter: blur*, dan tepian berbayang halus yang memberikan kesan tembus pandang bergaya premium (*Apple-like UI*).
- **Navigasi Dinamis**: 
  - **Desktop**: Menggunakan *Sidebar* (untuk Admin) dan *Tab Panel* melayang di atas konten (untuk User).
  - **Mobile**: Menggunakan *Bottom Navigation Bar* mirip aplikasi *mobile native* agar ergonomis ditekan menggunakan ibu jari.
- **Modern Soft Badges (`.badge-status`)**: Label status (*pending, in_progress, resolved*, dll.) yang menimpa kelas Bootstrap bawaan menjadi lencana berlatar pastel transparan dengan warna font yang tebal dan tajam (*modern soft UI*).
- **Komponen Ekstra**: Integrasi penuh *Fancybox* untuk interaksi media (foto lampiran) dan *DataTables* (dengan tema tembus pandang `datatable-glass`) untuk pengelolaan tabel yang mutakhir.

## 7. Stack Teknologi, Keamanan & Aturan Teknis (Rules)
Selain alur operasional, proyek ini sangat mematuhi standar keamanan, integritas data, dan *best practice* dalam pemrogramannya.

### 7.1 Teknologi Inti (Framework & Database)
- **Framework Utama**: CodeIgniter 3 (PHP)
- **Database**: PostgreSQL
- **Format ID Unik**: Menggunakan tipe data `UUID` (Universally Unique Identifier) sebagai *Primary Key* di tabel `users` dan `tickets`. Ini jauh lebih aman dari serangan pencarian sekuensial (*ID Guessing/Enumeration*) dibandingkan ID berbentuk angka (*Auto Increment*).

### 7.2 Data Penting (Key Data & Sessions)
Data vital yang menjaga alur aplikasi agar sesuai dengan hak akses (*Role-Based Access Control*):
- **`user_id`**: Disimpan dalam *session* saat login. Semua pembuatan tiket baru dan penarikan data riwayat sangat bergantung pada ID ini.
- **`role`**: Membedakan antara `admin` (Staf/Manajer IT) dan `user` (Pemohon/Supervisor).
- **`is_atasan` & `department_id`**: Variabel paling krusial untuk fitur Persetujuan. Data ini mengunci agar seorang Atasan hanya bisa menyetujui tiket dari bawahan yang berada di departemen yang sama.
- **`status` (pada tabel tiket)**: Rantai kendali utama aplikasi (`pending` -> `pending_it` -> `in_progress` -> `resolved`/`rejected`). Status inilah yang memindahkan tiket dari antrean User ke Atasan, ke Manajer IT, lalu ke teknisi IT.

### 7.3 Standar Keamanan (Security Rules)
Proyek ini mematuhi aturan keamanan mutlak:
- **Proteksi XSS (Cross-Site Scripting)**: Semua *output* teks yang berasal dari pengguna (terutama deskripsi tiket) wajib ditampilkan melalui perlindungan fungsi `htmlspecialchars()` di HTML untuk mencegah *scripting* jahat dieksekusi di *browser*.
- **Keamanan *Upload* Lampiran**: Validasi ketat dilakukan pada tingkat *Controller*. Sistem secara eksklusif hanya mengizinkan ekstensi gambar (`jpg, png, jpeg, gif`) dan langsung memblokir dokumen/skrip lain untuk menghindari injeksi *malware* via lampiran.
- **Anti SQL-Injection (Query Builder)**: Aplikasi dilarang keras mengeksekusi SQL *raw* (mentah) dengan variabel yang disisipkan langsung. Sistem selalu menggunakan metode **Query Builder CodeIgniter** (contoh: `$this->db->get_where()`) yang secara bawaan melakukan *escaping* string dengan aman.
- **Cegah Bypass URL (Auth Guard)**: Hak akses sangat ketat. Walaupun seorang pengguna mengetahui URL rahasia seperti `admin/approval_it`, *Controller* akan langsung mendepak mereka jika *Session Role* dan *Flag* Atasan IT-nya tidak terverifikasi.

### 7.4 Aturan Khusus (*Strict Rules*) PostgreSQL
- **Integritas Tipe Data (UUID vs String)**: Mengingat PostgreSQL sangat *strict* (ketat) mengenai pencocokan tipe data dibandingkan MySQL, maka operasi `JOIN` database tidak boleh asal menebak. Kesalahan `operator does not exist` berhasil dicegah dengan memastikan bahwa tidak ada komparasi buta (*blind cast*) antara kolom bertipe `UUID` murni dengan string `VARCHAR` tanpa penanganan eksplisit dalam rancangan *model*.

---

*Kesimpulan Akhir: Proyek IT Helpdesk ini bukan hanya menawarkan alur kerja operasional (SOP) dengan hierarki persetujuan bertingkat yang rapi (Atasan Departemen -> Atasan IT -> Teknisi), melainkan juga berdiri di atas fondasi kode (CodeIgniter & PostgreSQL) yang kuat, aman dari celah keamanan klasik, serta dibalut dengan estetika antarmuka UI/UX yang memukau dan modern.*
