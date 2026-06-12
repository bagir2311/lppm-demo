# LPPM Universitas Insan Mahardika - Landing Page & Superadmin CMS

Portal Landing Page Resmi dan Management System Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) Universitas Insan Mahardika (`lppm.mahardika.ac.id`).

Proyek ini dibangun menggunakan **Laravel 11**, **Blade (Layout & View)**, **TailwindCSS v4 (Styling Engine)**, dan **Alpine.js (Front-end Reactivity)**. Dirancang dengan visual berkelas premium yang bersih, akademik, *responsive*, serta mendukung *Dark Mode*.

---

## 🚀 Panduan Instalasi & Cara Menjalankan

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di mesin lokal Anda:

### 1. Prasyarat Sistem
Pastikan Anda memiliki kakas berikut yang telah terpasang di sistem Anda:
- **PHP >= 8.2**
- **Composer >= 2.x**
- **Node.js >= 20.x & NPM >= 10.x**

### 2. Kloning Proyek & Masuk Direktori
Arahkan terminal ke dalam folder proyek:
```bash
cd lppm-landingpage
```

### 3. Pasang Dependensi Backend (PHP/Laravel)
Pasang seluruh paket PHP yang dideklarasikan di `composer.json`:
```bash
composer install
```

### 4. Konfigurasi Environment File
Salin berkas template `.env.example` menjadi `.env` aktif dan buat kunci enkripsi aplikasi:
```bash
cp .env.example .env
php artisan key:generate
```
*Catatan: Konfigurasi `SESSION_DRIVER` diatur ke `file` agar in-memory database simulator berjalan stabil out-of-the-box tanpa dependensi database MySQL eksternal.*

### 5. Pasang Dependensi Frontend & Jalankan Bundler (Vite)
Pasang pustaka frontend (Tailwind CSS v4 dan Alpine.js) serta jalankan server kompilasi aset lokal:
```bash
npm install
npm run dev
```

### 6. Jalankan Server Lokal Laravel (Terminal Baru)
Buka tab terminal baru (tetap di direktori `lppm-landingpage`) dan jalankan server pengembangan bawaan artisan:
```bash
php artisan serve
```

Aplikasi kini dapat diakses secara lokal melalui peramban web pada alamat:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 📁 Peta Rute URL Aplikasi (Routing Table)

Aplikasi memiliki routing terstruktur yang terbagi menjadi 3 gerbang utama:

### 1. Portal Publik / Landing Page
- **`GET /`** : Halaman utama LPPM Universitas Insan Mahardika (12 section interaktif).
- **`GET /dosen/{slug}`** : Profil portofolio akademis dosen lengkap dengan riwayat hibah riset, pengmas, dan daftar publikasi.
- **`GET /portal`** : Hub gerbang aplikasi terintegrasi (SIMPEN, SIMPENGMAS, SIAKAD, dsb) dengan filter peran.

### 2. Keycloak SSO (Simulated Redirections)
- **`GET /sso-login`** : Mengarahkan pengguna langsung ke panel autentikasi Keycloak Universitas Insan Mahardika.
- **`GET /sso/callback`** : Endpoint penangkap callback token respons SSO.

### 3. Superadmin CMS Panel (Protected)
- **`GET /admin/login`** : Halaman masuk superadmin.
- **`POST /admin/login`** : Autentikasi kredensial administrator.
- **`GET /admin/logout`** : Keluar dari sesi administratif.
- **`GET /admin/dashboard`** : Ringkasan statistik data web LPPM.
- **`GET /admin/announcements`** : Manajemen rilis pengumuman & popup.
- **`GET /admin/lecturers`** : Manajemen direktori profil dosen & kepakaran.
- **`GET /admin/documents`** : Manajemen berkas SOP, peraturan, & panduan.
- **`GET /admin/journals`** : Manajemen link portal & jurnal kesehatan.
- **`GET /admin/settings`** : Manajemen variabel global situs (Headline Hero, Kontak, Sosmed).
- **`POST /admin/reset`** : Reset instan in-memory database kembali ke data default akademis.

---

## 🔐 Kredensial Akses Superadmin CMS (Simulasi)

Gunakan akun di bawah ini untuk masuk ke dalam Superadmin Dashboard:
- **Email**: `superadmin@mahardika.ac.id`
- **Kata Sandi**: `password`

---

## 💾 In-Memory Session Database Engine (`LPPMDataManager.php`)

Untuk mempermudah pengujian fitur CRUD CMS admin secara instan tanpa membutuhkan instalasi database SQL lokal yang rumit, proyek ini dilengkapi dengan **In-Memory Session Database Engine** yang berlokasi di:
📂 `app/Helpers/LPPMDataManager.php`

### Cara Kerja:
1. Saat pertama kali sistem diakses, database helper akan memeriksa ketiadaan session state dan me-seeding data akademis default yang sangat detail ke dalam Laravel Session (`session('lppm_announcements')`, `session('lppm_lecturers')`, dll).
2. Seluruh form CRUD (tambah, ubah, hapus) di dalam superadmin panel akan memutasi session array ini secara real-time.
3. Seluruh perubahan data dapat langsung disaksikan di halaman publik landing page, portal, dan profil dosen secara instan sepanjang sesi browser Anda aktif.
4. Anda dapat menggunakan tombol **"Pulihkan Database Awal"** di dashboard admin untuk mengembalikan data ke default bawaan awal kapan saja.

---

## 🎨 Spesifikasi Desain & Palet Warna Mahardika

Sistem ini didesain premium dan konsisten mengadopsi spesifikasi identitas visual **DESIGN_MAHARDIKA.md**:
- **Navy (#00295f)**: Warna identitas utama akademis.
- **Merah (#a80808)**: Warna aksen peringatan, pengumuman penting, dan highlight prioritas.
- **Hijau (#58813d)**: Warna aksen sukses, status aktif, hibah pkm, dan indikator peer-reviewed.
- **Slate Gelap (#0b1220)**: Background gelap utama untuk rendering Dark Mode premium yang nyaman di mata.
- **Micro-Animations & Glassmorphism**: Transisi halus di setiap elemen hover, blur-filter halus pada sticky navbar, serta modal pop-up bertenaga Alpine.js.
# lppm-mahardika
# lppm-mahardika
