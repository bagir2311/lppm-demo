<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class LPPMDataManager
{
    /**
     * Initialize all datasets in Session if they are not already set.
     */
    public static function init()
    {
        if (!Session::has('lppm_initialized')) {
            Session::put('lppm_statistics', self::getDefaultStatistics());
            Session::put('lppm_announcements', self::getDefaultAnnouncements());
            Session::put('lppm_lecturers', self::getDefaultLecturers());
            Session::put('lppm_documents', self::getDefaultDocuments());
            Session::put('lppm_portals', self::getDefaultPortals());
            Session::put('lppm_timeline', self::getDefaultTimeline());
            Session::put('lppm_settings', self::getDefaultSettings());
            Session::put('lppm_initialized', true);
        }
    }

    /**
     * Get Statistics
     */
    public static function getStatistics()
    {
        self::init();
        return Session::get('lppm_statistics');
    }

    /**
     * Get Announcements
     */
    public static function getAnnouncements()
    {
        self::init();
        return Session::get('lppm_announcements');
    }

    /**
     * Get Lecturers
     */
    public static function getLecturers()
    {
        try {
            $dbLecturers = \App\Models\Lecturer::where('is_active', true)
                ->where('status', 'published')
                ->latest()
                ->get();
            if ($dbLecturers->isNotEmpty()) {
                return $dbLecturers;
            }
        } catch (\Throwable $e) {}

        self::init();
        return Session::get('lppm_lecturers');
    }

    /**
     * Get Documents
     */
    public static function getDocuments()
    {
        self::init();
        return Session::get('lppm_documents');
    }

    /**
     * Get Portals
     */
    public static function getPortals()
    {
        self::init();
        return Session::get('lppm_portals');
    }

    /**
     * Get Timeline
     */
    public static function getTimeline()
    {
        self::init();
        return Session::get('lppm_timeline');
    }

    /**
     * Get Settings
     */
    public static function getSettings()
    {
        self::init();
        $settings = Session::get('lppm_settings', []);
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $dbSettings = \App\Models\Setting::getAllAsArray();
                if (!empty($dbSettings)) {
                    $settings = array_merge($settings, $dbSettings);
                }
            }
        } catch (\Throwable $e) {}

        return $settings;
    }

    /**
     * Save/Update Datasets
     */
    public static function saveAnnouncements($data)
    {
        Session::put('lppm_announcements', $data);
        self::updateStatisticsCount();
    }

    public static function saveLecturers($data)
    {
        Session::put('lppm_lecturers', $data);
        self::updateStatisticsCount();
    }

    public static function saveDocuments($data)
    {
        Session::put('lppm_documents', $data);
        self::updateStatisticsCount();
    }

    public static function savePortals($data)
    {
        Session::put('lppm_portals', $data);
    }

    public static function saveSettings($data)
    {
        Session::put('lppm_settings', $data);
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                foreach ($data as $key => $val) {
                    \App\Models\Setting::set($key, is_array($val) ? json_encode($val) : (string)$val);
                }
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Keep statistics counts aligned dynamically
     */
    private static function updateStatisticsCount()
    {
        $stats = self::getStatistics();
        $lecturers = self::getLecturers();
        $docs = self::getDocuments();

        foreach ($stats as &$stat) {
            if ($stat['icon'] === 'users') {
                $stat['value'] = (string)count($lecturers);
            }
        }
        Session::put('lppm_statistics', $stats);
    }

    /**
     * Reset to defaults
     */
    public static function resetToDefault()
    {
        Session::forget('lppm_initialized');
        Session::forget('lppm_statistics');
        Session::forget('lppm_announcements');
        Session::forget('lppm_lecturers');
        Session::forget('lppm_documents');
        Session::forget('lppm_portals');
        Session::forget('lppm_timeline');
        Session::forget('lppm_settings');
        self::init();
    }

    // ==========================================
    // DEFAULT DATA GENERATORS
    // ==========================================

    private static function getDefaultStatistics()
    {
        return [
            [
                'label' => 'Dosen Tetap',
                'value' => '6',
                'icon' => 'users',
                'bg' => 'bg-mahardika-navy-pale',
                'text' => 'text-mahardika-navy'
            ],
            [
                'label' => 'Penelitian Aktif',
                'value' => '38',
                'icon' => 'flask',
                'bg' => 'bg-sky-accent',
                'text' => 'text-mahardika-navy-soft'
            ],
            [
                'label' => 'Pengabdian Aktif',
                'value' => '25',
                'icon' => 'hand-heart',
                'bg' => 'bg-success-green-pale',
                'text' => 'text-success-green'
            ],
            [
                'label' => 'Publikasi Ilmiah',
                'value' => '312',
                'icon' => 'book-open',
                'bg' => 'bg-mahardika-navy-pale',
                'text' => 'text-mahardika-navy'
            ],
            [
                'label' => 'Mitra Kerja Sama',
                'value' => '18',
                'icon' => 'award',
                'bg' => 'bg-alert-red-pale',
                'text' => 'text-alert-red'
            ],
        ];
    }

    private static function getDefaultAnnouncements()
    {
        return [
            [
                'id' => 1,
                'title' => 'Penerimaan Proposal Hibah Penelitian & Pengabdian Masyarakat Internal TA 2026/2027',
                'date' => '18 Mei 2026',
                'category' => 'Penting',
                'category_type' => 'important',
                'summary' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) Universitas Insan Mahardika secara resmi membuka penerimaan usulan proposal penelitian dan pengabdian masyarakat skema hibah internal bagi seluruh dosen tetap mulai tanggal 18 Mei hingga 15 Juni 2026.',
                'is_important' => true,
                'is_new' => true,
                'file_size' => '2.4 MB',
                'link' => '#',
                'show_popup' => true,
                'start_date' => '2026-05-18',
                'end_date' => '2026-06-15'
            ],
            [
                'id' => 2,
                'title' => 'Call for Articles JKM - Jurnal Kesehatan Mahardika Volume 8 Nomor 2',
                'date' => '12 Mei 2026',
                'category' => 'Jurnal',
                'category_type' => 'hibah',
                'summary' => 'Undangan kontribusi artikel ilmiah hasil riset kesehatan bagi akademisi dan praktisi untuk diterbitkan pada Jurnal Kesehatan Mahardika (JKM) Vol. 8 No. 2 edisi Agustus 2026. Submit dibuka sampai dengan 10 Juli 2026.',
                'is_important' => false,
                'is_new' => true,
                'file_size' => '1.5 MB',
                'link' => 'https://jurnal.mahardika.ac.id/index.php/jkm',
                'show_popup' => false,
                'start_date' => '2026-05-12',
                'end_date' => '2026-07-10'
            ],
            [
                'id' => 3,
                'title' => 'Sosialisasi & Workshop Penyusunan Proposal Hibah Penelitian Kemdikbudristek Tahun 2026',
                'date' => '05 Mei 2026',
                'category' => 'Workshop',
                'category_type' => 'baru',
                'summary' => 'Meningkatkan persentase kelulusan usulan dosen nasional, LPPM menyelenggarakan pembekalan teknis penulisan proposal, sinkronisasi klaster SINTA, serta trik lolos review pendanaan Kemdikbudristek secara hybrid.',
                'is_important' => false,
                'is_new' => false,
                'file_size' => '950 KB',
                'link' => '#',
                'show_popup' => false,
                'start_date' => '2026-05-05',
                'end_date' => '2026-05-05'
            ],
            [
                'id' => 4,
                'title' => 'Pengumuman Hasil Desk Evaluasi Laporan Akhir Hibah Pengabdian Batch I 2025',
                'date' => '28 April 2026',
                'category' => 'Pengumuman',
                'category_type' => 'aktif',
                'summary' => 'Diberitahukan kepada seluruh dosen pelaksana pengmas pendanaan institusi tahun 2025 bahwa penilaian laporan akhir, rekap kuitansi, dan draf luaran artikel jurnal pengabdian telah selesai dinilai reviewer.',
                'is_important' => false,
                'is_new' => false,
                'file_size' => '1.2 MB',
                'link' => '#',
                'show_popup' => false,
                'start_date' => '2026-04-28',
                'end_date' => '2026-04-28'
            ]
        ];
    }

    private static function getDefaultLecturers()
    {
        return [
            [
                'name' => 'Dr. H. Ahmad Fauzi, M.Kes.',
                'slug' => 'ahmad-fauzi',
                'nidn' => '0412087501',
                'expertise' => 'Epidemiologi & Kesehatan Komunitas',
                'faculty' => 'Fakultas Ilmu Kesehatan',
                'study_program' => 'Kesmas',
                'publications' => '24',
                'citations' => '118',
                'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=256',
                'email' => 'ahmad.fauzi@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dummy_fauzi',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/0412087501',
                'scopus_url' => null,
                'research_topics' => ['Faktor Risiko Penyakit Tidak Menular pada Urban Slum', 'Analisis Kebijakan Kesehatan Puskesmas Jawa Barat', 'Dampak Promosi Kesehatan Digital terhadap Gaya Hidup Remaja'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Studi Epidemiologi Penyakit Diabetes Melitus Tipe 2 di Kawasan Pesisir Cirebon', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Pengembangan Model Intervensi Kesehatan Komunitas Berbasis Posyandu Prima', 'role' => 'Anggota Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pemberdayaan Kader Posyandu dalam Deteksi Dini Stunting di Desa Kertawinangun', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Edukasi PHBS dan Sanitasi Lingkungan Sekolah Dasar di Kecamatan Harjamukti', 'role' => 'Ketua Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'Epidemiological Analysis of Hypertension Risk Factors in Coastal Communities', 'journal' => 'JKM (Jurnal Kesehatan Mahardika)', 'citation' => '12'],
                    ['year' => '2024', 'title' => 'The Role of Community-Based POSYANDU in Preventive Healthcare', 'journal' => 'International Journal of Public Health Science', 'citation' => '8']
                ]
            ],
            [
                'name' => 'Prof. Dr. Retno Wahyuni, M.Kom.',
                'slug' => 'retno-wahyuni',
                'nidn' => '0402038002',
                'expertise' => 'Artificial Intelligence & Sistem Informasi Medis',
                'faculty' => 'Fakultas Ilmu Komputer',
                'study_program' => 'Informatika',
                'publications' => '42',
                'citations' => '326',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=256',
                'email' => 'retno.wahyuni@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dummy_retno',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/0402038002',
                'scopus_url' => null,
                'research_topics' => ['Algoritma Deep Learning untuk Diagnosis Kanker Payudara', 'Sistem Rekomendasi Farmasi Berbasis Ontologi Medis', 'Optimasi Layanan Rekam Medis Elektronik'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Rancang Bangun Sistem AI untuk Prediksi Awal Tuberkulosis dari Citra X-Ray Dada', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Penerapan Internet of Things (IoT) pada Pemantauan Pasien Rawat Jalan', 'role' => 'Ketua Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pelatihan Rancang Web Interaktif untuk Pemasaran UMKM di Desa Mundu', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Digitalisasi Administrasi Desa Berbasis Aplikasi Cloud di Cirebon Girang', 'role' => 'Anggota Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'Deep Learning Convolutional Neural Networks for Medical Image Classification', 'journal' => 'IEEE Access', 'citation' => '45'],
                    ['year' => '2024', 'title' => 'Smart EHR: Ontological System for Pharmacy Recommendation in Clinics', 'journal' => 'Journal of Medical Systems', 'citation' => '28']
                ]
            ],
            [
                'name' => 'Rosalia Rahayu, S.ST., M.Kes.',
                'slug' => 'rosalia-rahayu',
                'nidn' => '0418108901',
                'expertise' => 'Kesehatan Ibu dan Anak (KIA) & Kebidanan Komunitas',
                'faculty' => 'Fakultas Ilmu Kesehatan',
                'study_program' => 'Kebidanan',
                'publications' => '15',
                'citations' => '47',
                'avatar' => 'https://images.unsplash.com/photo-1594744803329-e58b31de215f?auto=format&fit=crop&q=80&w=256',
                'email' => 'rosalia.rahayu@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=rosalia-rahayu',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/0418108901',
                'scopus_url' => null,
                'research_topics' => ['Asuhan Kebidanan Komunitas Berkelanjutan', 'Pencegahan Komplikasi Persalinan pada Ibu Hamil Resiko Tinggi'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Evaluasi Kepatuhan Kunjungan Antenatal Care (ANC) di Puskesmas', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Pola Asuh Gizi Pertama 1000 Hari Kehidupan', 'role' => 'Anggota Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pemberdayaan Ibu Hamil dalam Senam Hamil Mandiri', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Konseling Laktasi Eksklusif Bagi Ibu Menyusui', 'role' => 'Ketua Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'Maternal Healthcare Delivery Analysis in Community Clinics', 'journal' => 'Indonesian Journal of Midwifery', 'citation' => '6'],
                    ['year' => '2024', 'title' => 'Community-Based Early Warning Score for High-Risk Pregnancies', 'journal' => 'JKM (Jurnal Kesehatan Mahardika)', 'citation' => '9']
                ]
            ],
            [
                'name' => 'Dede Setiawan, S.Kep., M.Kes.',
                'slug' => 'dede-setiawan',
                'nidn' => '0305057803',
                'expertise' => 'Manajemen Rekam Medis & Informasi Kesehatan',
                'faculty' => 'Fakultas Ilmu Kesehatan',
                'study_program' => 'Rekam Medis',
                'publications' => '18',
                'citations' => '94',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=256',
                'email' => 'dede.setiawan@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dede-setiawan',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/0305057803',
                'scopus_url' => null,
                'research_topics' => ['Standarisasi Rekam Medis Elektronik (RME) Faskes', 'Evaluasi Keamanan Data Pasien & Kodefikasi ICD-10'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Kesiapan Penerapan Rekam Medis Elektronik Terintegrasi SatuSehat', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Analisis Ketepatan Koding Diagnosis Morbiditas Pasien Rawat Inap', 'role' => 'Ketua Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pelatihan Petugas Administrasi Rekam Medis Klinik Pratama', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Digitalisasi Pengarsipan Rekam Medis Inaktif', 'role' => 'Ketua Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'Implementation of Electronic Medical Records: Readiness Assessment', 'journal' => 'Journal of Health Information Management', 'citation' => '14'],
                    ['year' => '2024', 'title' => 'Clinical Coding Accuracy in Secondary Care Hospitals', 'journal' => 'JKM (Jurnal Kesehatan Mahardika)', 'citation' => '10']
                ]
            ],
            [
                'name' => 'Ade Sutriyono, S.Kom., M.Si.',
                'slug' => 'ade-sutriyono',
                'nidn' => '040101031',
                'expertise' => 'Kecerdasan Buatan & Pengolahan Citra Medis',
                'faculty' => 'Fakultas Ilmu Komputer',
                'study_program' => 'Informatika',
                'publications' => '20',
                'citations' => '65',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=256',
                'email' => 'ade.sutriyono@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=ade-sutriyono',
                'sinta_url' => 'https://sinta.kemdiktisaintek.go.id/authors/profile/040101031',
                'scopus_url' => null,
                'research_topics' => ['Pengolahan Citra Medis untuk Deteksi Lesi', 'Pengembangan Sistem Informasi LPPM Berbasis Laravel'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Sistem Informasi Manajemen Riset Terintegrasi LPPM', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Segmentasi Citra USG dengan Convolutional Neural Network', 'role' => 'Ketua Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pendampingan Digitalisasi Layanan Kampus Berkelanjutan', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Workshop Literasi Komputasi untuk Siswa SMK Cirebon', 'role' => 'Ketua Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'Deep Learning Application in Biomedical Image Feature Extraction', 'journal' => 'IEEE Access', 'citation' => '11'],
                    ['year' => '2024', 'title' => 'Web-Based Integrated Research Management System Architecture', 'journal' => 'Journal of Software Engineering', 'citation' => '8']
                ]
            ],
        ];
    }

    private static function getDefaultDocuments()
    {
        return [
            [
                'id' => 1,
                'title' => 'Buku Panduan Pengusulan Hibah Penelitian Internal Mahardika 2026',
                'category' => 'Panduan Internal',
                'type' => 'PDF',
                'size' => '2.4 MB',
                'link' => '#',
                'status' => 'Published'
            ],
            [
                'id' => 2,
                'title' => 'Buku Panduan Pengusulan Hibah Pengabdian Masyarakat Mahardika 2026',
                'category' => 'Panduan Internal',
                'type' => 'PDF',
                'size' => '2.1 MB',
                'link' => '#',
                'status' => 'Published'
            ],
            [
                'id' => 3,
                'title' => 'Template Proposal Penelitian Internal Mahardika (Format DOCX)',
                'category' => 'Template',
                'type' => 'DOCX',
                'size' => '350 KB',
                'link' => '#',
                'status' => 'Published'
            ],
            [
                'id' => 4,
                'title' => 'Template Proposal Pengabdian Masyarakat Mahardika (Format DOCX)',
                'category' => 'Template',
                'type' => 'DOCX',
                'size' => '348 KB',
                'link' => '#',
                'status' => 'Published'
            ],
            [
                'id' => 5,
                'title' => 'SOP Pelaksanaan Penelitian Dosen & Mahasiswa Universitas Insan Mahardika',
                'category' => 'SOP',
                'type' => 'PDF',
                'size' => '1.2 MB',
                'link' => '#',
                'status' => 'Published'
            ],
            [
                'id' => 6,
                'title' => 'SOP Pelaksanaan Pengabdian kepada Masyarakat Universitas Insan Mahardika',
                'category' => 'SOP',
                'type' => 'PDF',
                'size' => '1.1 MB',
                'link' => '#',
                'status' => 'Published'
            ],
            [
                'id' => 7,
                'title' => 'Panduan Penelitian & Pengabdian Kepada Masyarakat Kemdikbudristek Edisi XV 2025',
                'category' => 'Kemdikbudristek',
                'type' => 'PDF',
                'size' => '4.8 MB',
                'link' => '#',
                'status' => 'Published'
            ]
        ];
    }

    private static function getDefaultPortals()
    {
        return [
            'penelitian' => [
                'title' => 'Portal Penelitian',
                'domain' => 'penelitian.mahardika.ac.id',
                'description' => 'Sistem Informasi Manajemen Penelitian Dosen (Simpen) untuk pendaftaran proposal online, review double-blind, monitoring progres laporan, luaran paten, hingga unggah hasil riset.',
                'link' => 'https://penelitian.mahardika.ac.id'
            ],
            'pengmas' => [
                'title' => 'Portal Pengabdian',
                'domain' => 'pengmas.mahardika.ac.id',
                'description' => 'Sistem Layanan Pengabdian kepada Masyarakat (Simpengmas) yang mewadahi pengajuan program aksi sosial, edukasi masyarakat, kemitraan UMKM, desa binaan, dan laporan luaran sosial.',
                'link' => 'https://pengmas.mahardika.ac.id'
            ],
            'jkm' => [
                'title' => 'Jurnal Kesehatan Mahardika (JKM)',
                'domain' => 'jurnal.mahardika.ac.id/index.php/jkm',
                'description' => 'Penerbitan berkala ilmiah OJS resmi terakreditasi SINTA 3 bidang keperawatan, kebidanan, kesehatan masyarakat, farmasi, serta administrasi medis.',
                'link' => 'https://jurnal.mahardika.ac.id/index.php/jkm'
            ]
        ];
    }

    private static function getDefaultTimeline()
    {
        return [
            [
                'step' => '01',
                'title' => 'Informasi & Sosialisasi Program',
                'description' => 'LPPM merilis Buku Panduan resmi beserta Rencana Induk Penelitian (RIP) institusi, serta mengadakan sosialisasi mengenai skema, klasterisasi, dan kuota pendanaan.'
            ],
            [
                'step' => '02',
                'title' => 'Penyusunan & Pengajuan Proposal',
                'description' => 'Dosen menyusun proposal, menentukan roadmap riset/pengabdian, mengisi form orisinalitas, dan mengunggah dokumen proposal berformat PDF/DOCX secara digital di portal.'
            ],
            [
                'step' => '03',
                'title' => 'Verifikasi Administrasi',
                'description' => 'Sekretariat LPPM melakukan review administrative awal untuk memastikan keikutsertaan kepangkatan, NIDN aktif, kesesuaian template, kepatuhan sitasi, dan ketiadaan plagiasi.'
            ],
            [
                'step' => '04',
                'title' => 'Double-Blind Review Substantif',
                'description' => 'Proposal diserahkan secara anonim kepada 2 Reviewer (internal/eksternal tersertifikasi) untuk dinilai secara orisinalitas, kualitas keilmuan, luaran, dan kewajaran anggaran belanja.'
            ],
            [
                'step' => '05',
                'title' => 'Revisi Sesuai Masukan Tim Ahli',
                'description' => 'Dosen menerima masukan teknis dan substantif dari reviewer, lalu melakukan perbaikan proposal pada form revisi di portal dalam tenggat waktu yang ditentukan.'
            ],
            [
                'step' => '06',
                'title' => 'Approval & Penetapan SK Pemenang',
                'description' => 'LPPM bersama Senat Universitas melakukan sidang penetapan kelayakan usulan, penerbitan Surat Keputusan (SK) Rektorat, dan penandatanganan perjanjian kerja kontrak hibah.'
            ],
            [
                'step' => '07',
                'title' => 'Penyaluran Dana & Pelaksanaan',
                'description' => 'Pencairan dana dilakukan bertahap (tahap awal dan monitoring luaran), diikuti monitoring-evaluasi (Monev) berkala, penyusunan laporan kemajuan, hingga publikasi hasil akhir.'
            ]
        ];
    }

    private static function getDefaultSettings()
    {
        return [
            'hero_headline' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat',
            'hero_subheadline' => 'Portal resmi LPPM Universitas Insan Mahardika. Wadah kolaborasi, fasilitasi, dan diseminasi hasil karya ilmiah, inovasi, publikasi jurnal terakreditasi, serta pengabdian masyarakat yang terintegrasi.',
            'lppm_email' => 'lppm@mahardika.ac.id',
            'lppm_phone' => '(0231) 456-789',
            'lppm_address' => 'Jl. Mahardika Raya No. 45, Kompleks Kampus Pusat Universitas Insan Mahardika, Kota Cirebon, Jawa Barat, 45131.',
            'social_facebook' => '#',
            'social_instagram' => '#',
            'social_twitter' => '#',
            'social_youtube' => '#',
            'link_portal_penelitian' => 'https://penelitian.mahardika.ac.id',
            'link_portal_pengmas' => 'https://pengmas.mahardika.ac.id',
            'haki_coordinator_name' => 'Ibu Iffah',
            'haki_coordinator_contact' => '(0231) 456-789 / lppm@mahardika.ac.id',
        ];
    }
}
