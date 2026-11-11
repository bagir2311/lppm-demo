<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\SuratPengajuan;
use App\Models\BerkasPengajuan;
use App\Models\PenilaianReviewer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Data Dosen Universitas Insan Mahardika
        $dosensData = [
            [
                'nidn' => '0412088501',
                'nama' => 'Dr. Apt. Rina Kusuma, M.Farm.',
                'prodi' => 'Farmasi',
                'fakultas' => 'Fakultas Ilmu Kesehatan',
                'email' => 'rina.kusuma@mahardika.ac.id',
                'kepakaran' => 'Formulasi Obat Herbal & Bioteknologi Farmasi',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=256',
            ],
            [
                'nidn' => '0425038202',
                'nama' => 'Ns. Budi Santoso, M.Kep., Sp.Kep.Kom',
                'prodi' => 'Keperawatan',
                'fakultas' => 'Fakultas Ilmu Kesehatan',
                'email' => 'budi.santoso@mahardika.ac.id',
                'kepakaran' => 'Keperawatan Komunitas & Pencegahan Stunting',
                'avatar' => 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=256',
            ],
            [
                'nidn' => '0419118903',
                'nama' => 'Hendra Wijaya, S.Kom., M.T.',
                'prodi' => 'Teknologi Informasi',
                'fakultas' => 'Fakultas Teknik & Sistem Informasi',
                'email' => 'hendra.wijaya@mahardika.ac.id',
                'kepakaran' => 'Kecerdasan Buatan & Sistem Informasi Kesehatan',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=256',
            ],
            [
                'nidn' => '0408078704',
                'nama' => 'Dr. Dewi Anggraini, S.ST., M.Keb.',
                'prodi' => 'Kebidanan',
                'fakultas' => 'Fakultas Ilmu Kesehatan',
                'email' => 'dewi.anggraini@mahardika.ac.id',
                'kepakaran' => 'Kesehatan Ibu dan Anak (KIA) & Kebidanan Komunitas',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=256',
            ],
            [
                'nidn' => '0415029005',
                'nama' => 'Ahmad Rizal, S.E., M.M.',
                'prodi' => 'Manajemen Kesehatan',
                'fakultas' => 'Fakultas Ekonomi & Bisnis',
                'email' => 'ahmad.rizal@mahardika.ac.id',
                'kepakaran' => 'Manajemen Rumah Sakit & Ekonomi Kesehatan',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=256',
            ],
        ];

        $createdDosens = [];
        foreach ($dosensData as $data) {
            $data['slug'] = Str::slug($data['nama']);
            $createdDosens[] = Dosen::updateOrCreate(['nidn' => $data['nidn']], $data);
        }

        // 2. Multi-Role Users (RBAC)
        $adminUser = User::updateOrCreate(
            ['email' => 'superadmin@mahardika.ac.id'],
            [
                'name' => 'Superadmin LPPM Mahardika',
                'password' => Hash::make('password'),
                'role' => 'admin_lppm',
                'dosen_id' => null,
            ]
        );

        $dosenUser = User::updateOrCreate(
            ['email' => 'dosen@mahardika.ac.id'],
            [
                'name' => 'Dr. Apt. Rina Kusuma, M.Farm.',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'dosen_id' => $createdDosens[0]->id,
            ]
        );

        $reviewerUser = User::updateOrCreate(
            ['email' => 'reviewer@mahardika.ac.id'],
            [
                'name' => 'Prof. Dr. Ir. Gunawan Wibisono (Reviewer Utama)',
                'password' => Hash::make('password'),
                'role' => 'reviewer',
                'dosen_id' => $createdDosens[1]->id,
            ]
        );

        // 3. Data Penelitian (BIMA Kemdiktisaintek Style)
        $penelitians = [
            [
                'judul' => 'Pengembangan Nanoemulsi Ekstrak Daun Kelor (Moringa oleifera) Sebagai Agen Formulasi Suplementasi Anti-Stunting Terstandar',
                'abstrak' => 'Penelitian ini bertujuan untuk memformulasi nanoemulsi berbasis ekstrak daun kelor terstandardisasi flavonoid dan zat besi tinggi guna meningkatkan efisiensi penyerapan nutrisi pada balita indikasi stunting di wilayah pedesaan.',
                'tahun_pelaksanaan' => 2025,
                'skema' => 'Penelitian Terapan (PT)',
                'bidang_fokus' => 'Kesehatan & Obat',
                'ketua_dosen_id' => $createdDosens[0]->id,
                'status' => 'Didanai',
                'dana_disetujui' => 125000000,
                'file_pdf_url' => 'https://lppm.mahardika.ac.id/documents/riset-nanoemulsi-2025.pdf',
            ],
            [
                'judul' => 'Rancang Bangun Sistem Pakar Deteksi Dini Risiko Kesehatan Jiwa Remaja Berbasis Artificial Intelligence dan Mobile App',
                'abstrak' => 'Mengembangkan aplikasi kecerdasan buatan berbasis Mobile untuk skrining dini kesehatan mental siswa SMA, menggunakan algoritma Naive Bayes dan Natural Language Processing untuk menganalisis respons emosional.',
                'tahun_pelaksanaan' => 2025,
                'skema' => 'Penelitian Dosen Pemula (PDP)',
                'bidang_fokus' => 'Teknologi Informasi & Komunikasi',
                'ketua_dosen_id' => $createdDosens[2]->id,
                'status' => 'Didanai',
                'dana_disetujui' => 45000000,
                'file_pdf_url' => 'https://lppm.mahardika.ac.id/documents/ai-mental-health-2025.pdf',
            ],
            [
                'judul' => 'Model Intervensi Keperawatan Komunitas Berbasis Pemberdayaan Kader Posyandu Cerdas Dalam Penurunan Angka Kematian Ibu (AKI)',
                'abstrak' => 'Studi komparatif dan uji klinis intervensi keperawatan komunitas dengan memanfaatkan aplikasi pemantauan ibu hamil risiko tinggi oleh kader posyandu terintegrasi di Kabupaten Cirebon.',
                'tahun_pelaksanaan' => 2024,
                'skema' => 'Penelitian Fundamental (PF)',
                'bidang_fokus' => 'Kesehatan Masyarakat',
                'ketua_dosen_id' => $createdDosens[1]->id,
                'status' => 'Selesai',
                'dana_disetujui' => 95000000,
                'file_pdf_url' => 'https://lppm.mahardika.ac.id/documents/intervensi-aki-2024.pdf',
            ],
        ];

        $createdPenelitians = [];
        foreach ($penelitians as $pen) {
            $createdPenelitians[] = Penelitian::create($pen);
        }

        // 4. Data Pengabdian Kepada Masyarakat (BIMA PKM Style)
        $pengabdians = [
            [
                'judul' => 'Pemberdayaan Posyandu Cerdas Berbasis IoT dan Edukasi Gizi Seimbang Untuk Percepatan Penurunan Stunting di Desa Mekarsari',
                'abstrak' => 'Program pengabdian berupa penerapan timbangan digital IoT terhubung aplikasi ponsel dan pelatihan olahan pangan lokal bergizi tinggi bagi kader posyandu dan ibu balita.',
                'tahun_pelaksanaan' => 2025,
                'skema' => 'PKM (Pengabdian Kepada Masyarakat)',
                'bidang_fokus' => 'Kesehatan Masyarakat & Gizi',
                'ketua_dosen_id' => $createdDosens[1]->id,
                'status' => 'Didanai',
                'dana_disetujui' => 50000000,
                'lokasi_mitra' => 'Desa Mekarsari, Kab. Cirebon',
                'file_pdf_url' => 'https://lppm.mahardika.ac.id/documents/pkm-posyandu-2025.pdf',
            ],
            [
                'judul' => 'Pelatihan Digital Marketing dan Manajemen Pemasaran Jamu Tradisional Bagi Kelompok Usaha Bersama (KUB) Herbal Mandiri',
                'abstrak' => 'Pendampingan sertifikasi halal, pembuatan standar operasional produksi (SOP) higienis, serta pembuatan toko online dan e-catalog bagi pengrajin jamu tradisional.',
                'tahun_pelaksanaan' => 2025,
                'skema' => 'Pemberdayaan Desa Mitra (PDM)',
                'bidang_fokus' => 'Pemberdayaan Ekonomi & UMKM',
                'ketua_dosen_id' => $createdDosens[0]->id,
                'status' => 'Didanai',
                'dana_disetujui' => 60000000,
                'lokasi_mitra' => 'KUB Herbal Mandiri, Kuningan',
                'file_pdf_url' => 'https://lppm.mahardika.ac.id/documents/pkm-jamu-2025.pdf',
            ],
        ];

        $createdPengabdians = [];
        foreach ($pengabdians as $peng) {
            $createdPengabdians[] = Pengabdian::create($peng);
        }

        // 5. Data Surat Pengajuan (Otomasi Persuratan)
        SuratPengajuan::create([
            'user_id' => $dosenUser->id,
            'dosen_id' => $createdDosens[0]->id,
            'jenis_surat' => 'Surat Izin Penelitian',
            'nomor_surat' => 'B/102/LPPM-UM/III/2026',
            'judul_penelitian' => 'Pengembangan Nanoemulsi Ekstrak Daun Kelor (Moringa oleifera)',
            'lokasi_penelitian' => 'Laboratorium Farmasi & RSUD Waled Cirebon',
            'tanggal_mulai' => '2026-04-01',
            'tanggal_selesai' => '2026-10-01',
            'status' => 'Disetujui',
            'catatan_admin' => 'Syarat administrasi dan tempat penelitian terverifikasi.',
        ]);

        SuratPengajuan::create([
            'user_id' => $dosenUser->id,
            'dosen_id' => $createdDosens[2]->id,
            'jenis_surat' => 'Pengantar Etik Penelitian',
            'nomor_surat' => null,
            'judul_penelitian' => 'Rancang Bangun Sistem Pakar Deteksi Dini Risiko Kesehatan Jiwa Remaja',
            'lokasi_penelitian' => 'SMA Negeri 1 Cirebon',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_selesai' => '2026-09-01',
            'status' => 'Pending',
            'catatan_admin' => null,
        ]);

        // 6. Data Berkas & LPJ Keuangan
        BerkasPengajuan::create([
            'user_id' => $dosenUser->id,
            'dosen_id' => $createdDosens[0]->id,
            'penelitian_id' => $createdPenelitians[0]->id,
            'jenis_berkas' => 'LPJ Keuangan',
            'nama_file' => 'LPJ_Keuangan_Pengembangan_Nanoemulsi_2025.pdf',
            'file_path' => '/storage/uploads/lppm_berkas/lpj_sample.pdf',
            'ukuran_file' => '2.4 MB',
            'status' => 'Submitted',
            'catatan' => 'Laporan pertanggungjawaban dana 70% beserta bukti kuitansi pembelian bahan baku.',
        ]);

        // 7. Data Penilaian Reviewer
        PenilaianReviewer::create([
            'reviewer_id' => $reviewerUser->id,
            'penelitian_id' => $createdPenelitians[0]->id,
            'skor_metodologi' => 88,
            'skor_relevansi' => 90,
            'skor_kelayakan' => 85,
            'total_skor' => 88,
            'rekomendasi' => 'Diterima',
            'catatan_reviewer' => 'Metodologi pembentukan nanoemulsi sangat terstruktur dan luaran terukur.',
            'status' => 'Selesai',
        ]);

        PenilaianReviewer::create([
            'reviewer_id' => $reviewerUser->id,
            'penelitian_id' => $createdPenelitians[1]->id,
            'skor_metodologi' => 0,
            'skor_relevansi' => 0,
            'skor_kelayakan' => 0,
            'total_skor' => 0,
            'rekomendasi' => null,
            'catatan_reviewer' => null,
            'status' => 'Belum Dinilai',
        ]);

        // 8. Seeding Seluruh Data Dosen Berbagai Program Studi
        $this->call(LecturerSeeder::class);
    }
}
