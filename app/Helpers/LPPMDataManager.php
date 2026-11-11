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
        return Session::get('lppm_settings');
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
                'study_program' => 'S1 Kesehatan Masyarakat',
                'publications' => '24',
                'citations' => '118',
                'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=256',
                'email' => 'ahmad.fauzi@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dummy_fauzi',
                'sinta_url' => 'https://sinta.kemdikbud.go.id/authors/detail?id=dummy_fauzi',
                'scopus_url' => 'https://www.scopus.com/authid/detail.uri?authorId=dummy_fauzi',
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
                'study_program' => 'S1 Teknik Informatika',
                'publications' => '42',
                'citations' => '326',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=256',
                'email' => 'retno.wahyuni@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dummy_retno',
                'sinta_url' => 'https://sinta.kemdikbud.go.id/authors/detail?id=dummy_retno',
                'scopus_url' => 'https://www.scopus.com/authid/detail.uri?authorId=dummy_retno',
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
                'name' => 'apt. Rina Lestari, M.Farm.',
                'slug' => 'rina-lestari',
                'nidn' => '0418108901',
                'expertise' => 'Farmakologi & Pengembangan Obat Herbal',
                'faculty' => 'Fakultas Farmasi',
                'study_program' => 'S1 Farmasi Klinis',
                'publications' => '15',
                'citations' => '47',
                'avatar' => 'https://images.unsplash.com/photo-1594744803329-e58b31de215f?auto=format&fit=crop&q=80&w=256',
                'email' => 'rina.lestari@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dummy_rina',
                'sinta_url' => 'https://sinta.kemdikbud.go.id/authors/detail?id=dummy_rina',
                'scopus_url' => 'https://www.scopus.com/authid/detail.uri?authorId=dummy_rina',
                'research_topics' => ['Uji Aktivitas Antioksidan Ekstrak Daun Kelor Cirebon', 'Formulasi Sediaan Gel Penyembuh Luka dari Kulit Manggis', 'Evaluasi Kepatuhan Minum Obat Pasien Geriatri'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Karakterisasi Fitokimia dan Efek Antiinflamasi Temulawak Lokal', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Efektivitas Sediaan Emulsi Minyak Ikan Sidat Terhadap Nafsu Makan Balita', 'role' => 'Anggota Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pemanfaatan Apotek Hidup (TOGA) untuk Kemandirian Obat Keluarga di Desa Astana', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Penyuluhan Dagusibu (Dapatkan, Gunakan, Simpan, Buang) Obat di Kelurahan Harjamukti', 'role' => 'Ketua Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'In Vitro Antioxidant Activity of Moringa oleifera Leaf Extracts', 'journal' => 'Indonesian Journal of Pharmacy', 'citation' => '6'],
                    ['year' => '2024', 'title' => 'Formulation and Evaluation of Herbal Healing Gel from Garcinia mangostana', 'journal' => 'Pharmacognosy Journal', 'citation' => '9']
                ]
            ],
            [
                'name' => 'Dr. Ir. Hendra Wijaya, M.T.',
                'slug' => 'hendra-wijaya',
                'nidn' => '0305057803',
                'expertise' => 'Energi Terbarukan & Mekatronika Terapan',
                'faculty' => 'Fakultas Teknik',
                'study_program' => 'S1 Teknik Mesin',
                'publications' => '18',
                'citations' => '94',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=256',
                'email' => 'hendra.wijaya@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dummy_hendra',
                'sinta_url' => 'https://sinta.kemdikbud.go.id/authors/detail?id=dummy_hendra',
                'scopus_url' => 'https://www.scopus.com/authid/detail.uri?authorId=dummy_hendra',
                'research_topics' => ['Pengembangan Turbin Angin Sumbu Vertikal untuk Pesisir', 'Sistem Monitoring Sel Surya Berbasis IoT', 'Rancang Bangun Aktuator Robot Medis'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Optimasi Sudut Sudu Turbin Air Savonius untuk Pembangkit Skala Mikro', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Analisis Efisiensi Panel Surya Monokristalin dengan Sistem Pendingin Air', 'role' => 'Ketua Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pemasangan Lampu Jalan Tenaga Surya di Dusun Karangmangu', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Pelatihan Perawatan Mesin Pompa Air Pertanian Bagi Kelompok Tani Subur', 'role' => 'Ketua Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'Performance Optimization of Vertical Axis Wind Turbines in Low Wind Speeds', 'journal' => 'Journal of Renewable Energy', 'citation' => '14'],
                    ['year' => '2024', 'title' => 'Design of An Automated IoT-Based Solar panel Cleaner', 'journal' => 'Applied Mechanics and Materials', 'citation' => '10']
                ]
            ],
            [
                'name' => 'Nia Kusuma, S.Pd., M.Hum.',
                'slug' => 'nia-kusuma',
                'nidn' => '0429119102',
                'expertise' => 'Linguistik Terapan & Literasi Digital',
                'faculty' => 'Fakultas Keguruan & Ilmu Pendidikan',
                'study_program' => 'S1 Pendidikan Bahasa Inggris',
                'publications' => '12',
                'citations' => '35',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=256',
                'email' => 'nia.kusuma@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dummy_nia',
                'sinta_url' => 'https://sinta.kemdikbud.go.id/authors/detail?id=dummy_nia',
                'scopus_url' => 'https://www.scopus.com/authid/detail.uri?authorId=dummy_nia',
                'research_topics' => ['Penggunaan Aplikasi Pembelajaran Bahasa Asing Berbasis AI', 'Analisis Sosiolinguistik Dialek Lokal di Wilayah 3 Cirebon', 'Penerapan Blended Learning pada Guru'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Analisis Kebutuhan Pedagogis Pembelajaran Digital Guru Bahasa Cirebon', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Pengaruh Media Gim Interaktif Terhadap Penguasaan Kosakata Bahasa Inggris Anak', 'role' => 'Anggota Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pendampingan Pengajaran Bahasa Inggris Kreatif di Rumah Belajar Pintar', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Workshop Penyusunan Kurikulum Merdeka Bagi Guru PAUD se-Kecamatan Kedawung', 'role' => 'Ketua Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'AI Chatbots in Foreign Language Education: A Systematic Review', 'journal' => 'System Journal', 'citation' => '8'],
                    ['year' => '2024', 'title' => 'Linguistic Identity of Cirebon Border Communities: A Sociolinguistic Study', 'journal' => 'Language & Communication', 'citation' => '5']
                ]
            ],
            [
                'name' => 'Budi Santoso, S.E., M.M.',
                'slug' => 'budi-santoso',
                'nidn' => '0408078504',
                'expertise' => 'Ekonomi Pembangunan & Pemberdayaan UMKM',
                'faculty' => 'Fakultas Ekonomi & Bisnis',
                'study_program' => 'S1 Manajemen Bisnis',
                'publications' => '16',
                'citations' => '73',
                'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=256',
                'email' => 'budi.santoso@mahardika.ac.id',
                'scholar_url' => 'https://scholar.google.com/citations?user=dummy_budi',
                'sinta_url' => 'https://sinta.kemdikbud.go.id/authors/detail?id=dummy_budi',
                'scopus_url' => 'https://www.scopus.com/authid/detail.uri?authorId=dummy_budi',
                'research_topics' => ['Pengaruh Fintech Lending terhadap Pendapatan Pedagang Pasar', 'Strategi Manajemen Pemasaran Digital UMKM Kuliner Cirebon', 'Analisis Efektivitas Kredit Usaha Rakyat'],
                'research_history' => [
                    ['year' => '2025', 'title' => 'Studi Komparasi Akses Permodalan UMKM Sebelum dan Sesudah Digitalisasi', 'role' => 'Ketua Peneliti'],
                    ['year' => '2024', 'title' => 'Pengaruh Green Economy Terhadap Keputusan Konsumsi Masyarakat Urban', 'role' => 'Ketua Peneliti']
                ],
                'service_history' => [
                    ['year' => '2025', 'title' => 'Pelatihan Manajemen Keuangan dan Digital Branding Pengrajin Batik Trusmi', 'role' => 'Ketua Pengmas'],
                    ['year' => '2024', 'title' => 'Sosialisasi Pembuatan Izin Usaha Mikro Kecil (IUMK) Elektronik di Kecamatan Weru', 'role' => 'Ketua Pengmas']
                ],
                'selected_publications' => [
                    ['year' => '2025', 'title' => 'Financial Technology and the Growth of Micro Enterprises in West Java', 'journal' => 'Journal of Small Business Management', 'citation' => '15'],
                    ['year' => '2024', 'title' => 'Marketing Strategy and Brand Awareness of Culinary SMEs', 'journal' => 'Asia Pacific Journal of Marketing', 'citation' => '11']
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
        ];
    }
}
