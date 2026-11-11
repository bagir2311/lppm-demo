<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Lecturer;
use App\Helpers\LPPMDataManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds for all lecturers across study programs.
     */
    public function run(): void
    {
        $lecturersByProdi = [
            [
                'prodi' => 'Kebidanan',
                'fakultas' => 'Fakultas Ilmu Kesehatan',
                'lecturers' => [
                    [
                        'nama' => 'Lely Nurlaili',
                        'kepakaran' => 'Asuhan Kebidanan Fisiologis & Neonatus',
                    ],
                    [
                        'nama' => 'Rosalia Rahayu, S.ST., M.Kes.',
                        'kepakaran' => 'Kesehatan Ibu dan Anak (KIA) & Kebidanan Komunitas',
                    ],
                    [
                        'nama' => 'Sumarni, S.ST., M.M.',
                        'kepakaran' => 'Manajemen Pelayanan Kebidanan & Mutu Faskes',
                    ],
                    [
                        'nama' => 'Thia Oktiany, S.ST., M.K.M.',
                        'kepakaran' => 'Kesehatan Reproduksi & Promosi Kesehatan Ibu',
                    ],
                    [
                        'nama' => 'Yanti Susan, S.ST., M.Kes.',
                        'kepakaran' => 'Kebidanan Terapan, Gizi Ibu Hamil & Laktasi',
                    ],
                    [
                        'nama' => 'Pipit Indra Lestari, S.Tr., M.Kes.',
                        'kepakaran' => 'Deteksi Dini Komplikasi Obstetri & Neonatus',
                    ],
                    [
                        'nama' => 'Rina Utami',
                        'kepakaran' => 'Pelayanan Keluarga Berencana & Kesehatan Remaja',
                    ],
                ],
            ],
            [
                'prodi' => 'Keperawatan',
                'fakultas' => 'Fakultas Ilmu Kesehatan',
                'lecturers' => [
                    [
                        'nama' => 'Citra Setyo Dwi Andhini, S.Kep., M.Kep.',
                        'kepakaran' => 'Keperawatan Medikal Bedah & Onkologi',
                    ],
                    [
                        'nama' => 'Dwiyanti Purbasari, M.Kep.',
                        'kepakaran' => 'Keperawatan Maternitas & Kesehatan Wanita',
                    ],
                    [
                        'nama' => 'Erida Fadila, S.Kep., Ners., M.Kep.',
                        'kepakaran' => 'Keperawatan Gawat Darurat & Manajemen Kritis',
                    ],
                    [
                        'nama' => 'Ns. Dewi Erna Marisa, M.Kep.',
                        'kepakaran' => 'Keperawatan Anak & Tumbuh Kembang Pediatri',
                    ],
                    [
                        'nama' => 'Ns. Ahmad Syaripudin, S.Kep., M.M., M.Kep.',
                        'kepakaran' => 'Manajemen & Kepemimpinan Keperawatan Klinis',
                    ],
                    [
                        'nama' => 'Ns. Tantri, M.Kep.',
                        'kepakaran' => 'Keperawatan Jiwa & Terapi Modalitas Psikososial',
                    ],
                    [
                        'nama' => 'Ns. Endah Sari Purbaningsih, M.Kep.',
                        'kepakaran' => 'Keperawatan Komunitas & Pencegahan Stunting',
                    ],
                    [
                        'nama' => 'Ns. Muadi, M.Kep.',
                        'kepakaran' => 'Keperawatan Gerontik & Perawatan Lansia',
                    ],
                    [
                        'nama' => 'Ns. Ramli Effendi, M.Kep.',
                        'kepakaran' => 'Keperawatan Kardiovaskuler & Intervensi Kritis',
                    ],
                    [
                        'nama' => 'Uni Wahyuni, S.Kep., Ners., M.H.',
                        'kepakaran' => 'Etika Profesi & Hukum Kesehatan Keperawatan',
                    ],
                    [
                        'nama' => 'Nonok Karlina, M.Kep., Ns.Sp.Kep.MB',
                        'kepakaran' => 'Spesialis Keperawatan Medikal Bedah & Luka Modern',
                    ],
                    [
                        'nama' => 'Ns. Hasril Desiathul Hamdani Bafaraz, M.Kep.',
                        'kepakaran' => 'Keperawatan Paliatif & Perawatan Holistik',
                    ],
                    [
                        'nama' => 'Terry Melany, M.Kep.',
                        'kepakaran' => 'Fundamental Keperawatan & Patient Safety',
                    ],
                    [
                        'nama' => 'Lili Wahyuni R.',
                        'kepakaran' => 'Keperawatan Dasar & Farmakologi Klinis',
                    ],
                ],
            ],
            [
                'prodi' => 'Rekam Medis',
                'fakultas' => 'Fakultas Ilmu Kesehatan',
                'lecturers' => [
                    [
                        'nama' => 'Dede Setiawan, S.Kep., M.Kes.',
                        'kepakaran' => 'Sistem Rekam Medis Elektronik (RME) & Informasi RS',
                    ],
                    [
                        'nama' => 'Loura Weryco Latupeirissa, S.KM., M.M.',
                        'kepakaran' => 'Manajemen Informasi Kesehatan & Manajemen Mutu RME',
                    ],
                    [
                        'nama' => 'Riantina Luxiarti, S.KM., M.P.H.',
                        'kepakaran' => 'Analisis Data Rekam Medis & Manajemen Klaim Asuransi',
                    ],
                    [
                        'nama' => 'Sri Nurcahyati, S.KM., M.Epid.',
                        'kepakaran' => 'Epidemiologi Deskriptif & Statistik Pelayanan Medis',
                    ],
                    [
                        'nama' => 'Tuti Herawati, S.KM., M.M.',
                        'kepakaran' => 'Klasifikasi & Kodifikasi Klinis (ICD-10 / ICD-9-CM)',
                    ],
                    [
                        'nama' => 'Fardhoniandinna',
                        'kepakaran' => 'Teknologi Informasi Rekam Medis & Kerahasiaan Data Medis',
                    ],
                ],
            ],
            [
                'prodi' => 'Kesehatan Masyarakat',
                'fakultas' => 'Fakultas Ilmu Kesehatan',
                'lecturers' => [
                    [
                        'nama' => 'Jaenudin, S.KM., M.P.H.',
                        'kepakaran' => 'Epidemiologi Penyakit Menular & Kesehatan Lingkungan',
                    ],
                    [
                        'nama' => 'Ani Nurhaeni, S.KM., M.K.M.',
                        'kepakaran' => 'Promosi Kesehatan & Perilaku Hidup Bersih Sehat (PHBS)',
                    ],
                    [
                        'nama' => 'Elvi Yunianti, S.KM., M.K.M.',
                        'kepakaran' => 'Gizi Kesehatan Masyarakat, Ketahanan Pangan & Stunting',
                    ],
                    [
                        'nama' => 'Lili Amaliah, S.KM., M.M.',
                        'kepakaran' => 'Administrasi & Kebijakan Kesehatan (AKK)',
                    ],
                    [
                        'nama' => 'Yani Kamasturyani',
                        'kepakaran' => 'Kesehatan & Keselamatan Kerja (K3) Rumah Sakit & Industri',
                    ],
                    [
                        'nama' => 'Hediyana',
                        'kepakaran' => 'Biostatistik Kesehatan & Surveilans Epidemiologi',
                    ],
                ],
            ],
            [
                'prodi' => 'Informatika',
                'fakultas' => 'Fakultas Ilmu Komputer',
                'lecturers' => [
                    [
                        'nama' => 'Ade Sutriyono, S.Kom., M.Si.',
                        'kepakaran' => 'Kecerdasan Buatan & Pengolahan Citra Medis',
                    ],
                    [
                        'nama' => 'Dr. Indra Surya Permana, M.M.',
                        'kepakaran' => 'Tata Kelola TI, Enterprise Architecture & Sistem Informasi',
                    ],
                    [
                        'nama' => 'Mohamad Firdaus, M.Kom.',
                        'kepakaran' => 'Rekayasa Perangkat Lunak, Web & Mobile Health (mHealth)',
                    ],
                    [
                        'nama' => 'Syaiful Ramadhan, M.Kom.',
                        'kepakaran' => 'Jaringan Komputer, Keamanan Informasi & Cloud Computing',
                    ],
                    [
                        'nama' => 'Yasep Azzery, S.Pd., M.T.',
                        'kepakaran' => 'Internet of Things (IoT), Robotika & Sistem Tertanam',
                    ],
                    [
                        'nama' => 'Hendri Rosmawan',
                        'kepakaran' => 'Basis Data Spasial, Data Mining & Big Data Analytics',
                    ],
                ],
            ],
        ];

        // Daftar foto profil akademis profesional
        $avatarPool = [
            'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1573496799652-408c2ac9fe98?auto=format&fit=crop&q=80&w=256',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=256',
        ];

        $counter = 1;
        $lppmList = [];

        foreach ($lecturersByProdi as $group) {
            foreach ($group['lecturers'] as $item) {
                $nama = $item['nama'];
                $kepakaran = $item['kepakaran'];

                // 1. Generate NIDN dummy berkode urut (Contoh: 040101001, 040101002, dst.)
                $nidn = sprintf('040101%03d', $counter);

                // 2. Generate slug ramah URL dari nama dosen
                $baseSlug = Str::slug($nama);
                $slug = $baseSlug;
                $slugCount = 1;
                while (Dosen::where('slug', $slug)->where('nidn', '!=', $nidn)->exists()) {
                    $slug = $baseSlug . '-' . (++$slugCount);
                }

                // 3. Generate Email Akademik dummy otomatis (nama@mahardika.ac.id)
                $cleanName = preg_replace('/\b(Dr|Apt|Ns|Sp|Sp\.Kep\.MB|S\.ST|M\.Kes|M\.M|M\.K\.M|S\.Tr|S\.Kep|M\.Kep|Ners|M\.H|S\.KM|M\.P\.H|M\.Epid|S\.Kom|M\.Si|S\.Pd|M\.T)\b|\./i', '', $nama);
                $cleanName = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $cleanName));
                $emailPrefix = Str::slug($cleanName, '.');
                if (empty($emailPrefix)) {
                    $emailPrefix = Str::slug($nama, '.');
                }
                $email = $emailPrefix . '@mahardika.ac.id';

                // Pastikan email unik jika ada nama sama
                $emailCount = 1;
                while (User::where('email', $email)->where('name', '!=', $nama)->exists()) {
                    $email = $emailPrefix . (++$emailCount) . '@mahardika.ac.id';
                }

                // 4. Pilih Avatar
                $avatar = $avatarPool[($counter - 1) % count($avatarPool)];

                // 5. Buat Akun Login di tabel users (Role: dosen, Password default: NIDN)
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $nama,
                        'password' => Hash::make($nidn),
                        'role' => 'dosen',
                    ]
                );

                // 6. Buat Record Profil Dosen di tabel dosens / Lecturer
                $dosen = Lecturer::updateOrCreate(
                    ['nidn' => $nidn],
                    [
                        'user_id' => $user->id,
                        'nama' => $nama,
                        'prodi' => $group['prodi'],
                        'fakultas' => $group['fakultas'],
                        'email' => $email,
                        'slug' => $slug,
                        'kepakaran' => $kepakaran,
                        'avatar' => $avatar,
                        'status' => 'published',
                        'is_active' => true,
                    ]
                );

                // Sinkronkan dosen_id pada tabel users
                $user->update(['dosen_id' => $dosen->id]);

                // 7. Siapkan struktur untuk LPPMDataManager
                $lppmList[] = [
                    'name' => $nama,
                    'slug' => $slug,
                    'nidn' => $nidn,
                    'expertise' => $kepakaran,
                    'faculty' => $group['fakultas'],
                    'study_program' => $group['prodi'],
                    'publications' => (string) rand(4, 25),
                    'citations' => (string) rand(12, 95),
                    'avatar' => $avatar,
                    'email' => $email,
                    'scholar_url' => 'https://scholar.google.com/citations?user=' . $slug,
                    'sinta_url' => 'https://sinta.kemdikbud.go.id/authors/detail?id=' . $nidn,
                    'scopus_url' => '#',
                    'research_topics' => ['Riset Bidang ' . $group['prodi'], $kepakaran],
                ];

                $counter++;
            }
        }

        // Sinkronisasi data ke LPPMDataManager session
        try {
            LPPMDataManager::saveLecturers($lppmList);
        } catch (\Throwable $e) {
            // Abaikan jika running via CLI tanpa session aktif
        }

        // Refresh cache data dosen publik
        Cache::forget('list_dosen_publik');

        $this->command->info('Berhasil men-seed ' . ($counter - 1) . ' Data Dosen Mahardika beserta Akun Login User.');
    }
}
