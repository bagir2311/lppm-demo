<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SuratController extends Controller
{
    /**
     * Konversi angka bulan ke angka Romawi.
     */
    public static function getBulanRomawi($bulan = null)
    {
        $bulan = $bulan ? (int)$bulan : (int)date('n');
        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romawi[$bulan] ?? 'I';
    }

    /**
     * Otomatis generate nomor surat resmi format: [Urutan 4 Digit]/LPPM UIM/[Bulan Romawi]/[Tahun].
     */
    public static function generateNomorSuratBaru($tahun = null, $bulan = null)
    {
        $tahun = $tahun ?: date('Y');
        $bulanRomawi = self::getBulanRomawi($bulan);

        // Ambil seluruh surat di tahun terkait yang sudah memiliki nomor surat
        $surats = Surat::whereNotNull('nomor_surat')
            ->where(function ($q) use ($tahun) {
                $q->whereYear('created_at', $tahun)
                  ->orWhere('nomor_surat', 'like', "%/{$tahun}%")
                  ->orWhere('nomor_surat', 'like', "%/{$tahun}");
            })
            ->get();

        $maxUrutan = 0;
        foreach ($surats as $s) {
            $nomor = trim($s->nomor_surat);
            // Pola 1: Angka di awal (Contoh: 0019/LPPM UIM/XI/2026 atau 19/LPPM/...)
            if (preg_match('/^(\d+)\//', $nomor, $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxUrutan) {
                    $maxUrutan = $num;
                }
            }
            // Pola 2: Angka di akhir (Contoh: LPPM/UM/2026/19)
            elseif (preg_match('/\/(\d+)$/', $nomor, $matches)) {
                $num = (int)$matches[1];
                if ($num > $maxUrutan) {
                    $maxUrutan = $num;
                }
            }
        }

        if ($maxUrutan === 0 && $surats->count() > 0) {
            $maxUrutan = $surats->count();
        }

        $nextUrutan = $maxUrutan + 1;
        $urutanPadded = str_pad($nextUrutan, 4, '0', STR_PAD_LEFT);

        return "{$urutanPadded}/UIM/{$bulanRomawi}/{$tahun}";
    }

    /**
     * Tampilkan meja kerja admin untuk verifikasi & approval persuratan.
     */
    public function adminIndex()
    {
        $surats = Surat::orderBy('created_at', 'desc')->get();
        $totalSurat = $surats->count();
        $pendingSurat = $surats->where('status', 'pending')->count();
        $approvedSurat = $surats->where('status', 'approved')->count();
        $autoNomorSurat = self::generateNomorSuratBaru();

        return view('admin.surat.index', compact('surats', 'totalSurat', 'pendingSurat', 'approvedSurat', 'autoNomorSurat'));
    }

    /**
     * Dapatkan daftar dosen terpadu dari database dosen & user bertipe dosen.
     */
    private function getDosenList()
    {
        $dosens = collect();

        // 1. Ambil dari tabel dosens
        if (class_exists(Dosen::class)) {
            $dbDosens = Dosen::orderBy('nama', 'asc')->get();
            foreach ($dbDosens as $d) {
                $dosens->push((object)[
                    'id' => (string) $d->id,
                    'name' => $d->nama,
                    'nama' => $d->nama,
                    'nidn' => $d->nidn ?: '-',
                    'prodi' => $d->prodi ?: ($d->fakultas ?? 'Fakultas Ilmu Kesehatan'),
                ]);
            }
        }

        // 2. Ambil dari tabel users dengan role dosen (jika belum tercatat)
        $userDosens = \App\Models\User::where('role', 'dosen')->with('dosen')->get();
        foreach ($userDosens as $u) {
            $name = $u->name;
            $nidn = $u->dosen?->nidn ?? $u->nidn ?? $u->identity_number ?? '-';
            $prodi = $u->dosen?->prodi ?? 'Fakultas Ilmu Kesehatan';

            $alreadyExists = $dosens->first(function($item) use ($name, $nidn) {
                return ($nidn !== '-' && $item->nidn === $nidn) || strtolower($item->name) === strtolower($name);
            });

            if (!$alreadyExists) {
                $dosens->push((object)[
                    'id' => 'user_' . $u->id,
                    'name' => $name,
                    'nama' => $name,
                    'nidn' => $nidn,
                    'prodi' => $prodi,
                ]);
            }
        }

        return $dosens;
    }

    /**
     * Tampilkan daftar seluruh pengajuan surat.
     */
    public function index()
    {
        $surats = Surat::orderBy('created_at', 'desc')->get();
        $dosens = $this->getDosenList();
        $autoNomorSurat = self::generateNomorSuratBaru();

        return view('surat.index', compact('surats', 'dosens', 'autoNomorSurat'));
    }

    /**
     * Form mandiri untuk pengajuan surat baru.
     */
    public function create()
    {
        $dosens = $this->getDosenList();

        return view('surat.create', compact('dosens'));
    }

    /**
     * Dosen mengajukan surat baru (status default: 'pending').
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'keperluan' => 'nullable|string',
            'instansi_tujuan' => 'nullable|string|max:255',
            'lokasi_penelitian' => 'nullable|string|max:255',
            'nama_dosen' => 'nullable|string|max:255',
            'nidn' => 'nullable|string|max:50',
            'prodi' => 'nullable|string|max:255',
        ]);

        $namaDosen = $request->input('nama_dosen');
        $nidn = $request->input('nidn');
        $prodi = $request->input('prodi');
        $keperluan = $request->input('keperluan') ?? $request->input('judul_penelitian');
        $instansiTujuan = $request->input('instansi_tujuan');
        $lokasiPenelitian = $request->input('lokasi_penelitian') ?: $instansiTujuan;

        // Jika form mengirim dosen_id, gunakan data dari model Dosen atau User
        if ($request->filled('dosen_id')) {
            $dosenId = $request->input('dosen_id');
            if (str_starts_with($dosenId, 'user_')) {
                $userId = str_replace('user_', '', $dosenId);
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $namaDosen = $namaDosen ?: $user->name;
                    $nidn = $nidn ?: ($user->nidn ?? $user->identity_number ?? '-');
                    $prodi = $prodi ?: ($user->dosen?->prodi ?? 'Fakultas Ilmu Kesehatan');
                }
            } elseif (class_exists(Dosen::class)) {
                $dosen = Dosen::find($dosenId);
                if ($dosen) {
                    $namaDosen = $namaDosen ?: $dosen->nama;
                    $nidn = $nidn ?: $dosen->nidn;
                    $prodi = $prodi ?: ($dosen->prodi ?? 'Fakultas Ilmu Kesehatan');
                }
            }
        }

        // Fallback default jika nama dosen masih kosong
        if (empty($namaDosen)) {
            $namaDosen = auth()->check() ? auth()->user()->name : 'Dosen Universitas Insan Mahardika';
            $prodi = $prodi ?: 'Kesehatan';
        }

        // Fallback NIDN agar tidak pernah null
        if (empty($nidn)) {
            $nidn = $request->nidn ?? auth()->user()?->nidn ?? auth()->user()?->identity_number ?? (auth()->user()?->dosen?->nidn ?? '-');
        }
        if (empty(trim((string) $nidn))) {
            $nidn = '-';
        }

        if (empty($keperluan)) {
            $keperluan = 'Permohonan pelaksanaan riset/pengabdian di lapangan';
        }

        if (empty($instansiTujuan)) {
            $instansiTujuan = 'Instansi Terkait / Lokasi Penelitian Mitra';
        }

        $anggotaList = [];
        $isIzinPenelitian = str_contains(strtolower($request->jenis_surat), 'izin penelitian') || str_contains(strtolower($request->jenis_surat), 'ijin penelitian');
        if ($isIzinPenelitian && $request->has('anggota_nama') && is_array($request->input('anggota_nama')) && count(array_filter($request->input('anggota_nama'))) > 0) {
            $anggotaList[] = [
                'nama' => $namaDosen,
                'judul' => $keperluan,
            ];
            $arrJudul = $request->input('anggota_judul', []);
            foreach ($request->input('anggota_nama') as $idx => $namaAnggota) {
                if (!empty(trim($namaAnggota))) {
                    $anggotaList[] = [
                        'nama' => trim($namaAnggota),
                        'judul' => !empty(trim($arrJudul[$idx] ?? '')) ? trim($arrJudul[$idx]) : '',
                    ];
                }
            }
        }

        $suratData = [
            'jenis_surat' => $request->jenis_surat,
            'nama_dosen' => $namaDosen,
            'nidn' => $nidn,
            'prodi' => $prodi,
            'keperluan' => $keperluan,
            'instansi_tujuan' => $instansiTujuan,
            'lokasi_penelitian' => $lokasiPenelitian,
            'anggota' => !empty($anggotaList) ? $anggotaList : null,
            'status' => 'pending',
            'qr_code_hash' => null,
            'approved_at' => null,
        ];

        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('surats', 'lokasi_penelitian')) {
                unset($suratData['lokasi_penelitian']);
            }
        } catch (\Throwable $e) {
            unset($suratData['lokasi_penelitian']);
        }

        $surat = Surat::create($suratData);

        return redirect()->route('surat.index')->with('success', 'Pengajuan surat ' . $surat->jenis_surat . ' berhasil dikirim! Menunggu persetujuan admin LPPM.');
    }

    /**
     * Admin menyetujui surat, otomatis buat nomor_surat (LPPM/UM/Tahun/ID) dan qr_code_hash (MD5).
     */
    public function approve(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role === 'dosen') {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat menyetujui dokumen ini.');
        }

        // Validasi hak akses: Hanya Admin LPPM yang memiliki wewenang untuk menyetujui surat
        $isAdmin = session('admin_logged_in') 
            || (auth()->check() && in_array(auth()->user()->role, ['admin', 'admin_lppm'])) 
            || in_array(session('user_role'), ['admin', 'admin_lppm']);

        if (!$isAdmin) {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat menyetujui dokumen ini.');
        }

        $surat = Surat::findOrFail($id);

        $nomorSurat = $request->filled('nomor_surat') 
            ? trim($request->input('nomor_surat')) 
            : self::generateNomorSuratBaru();
            
        $qrCodeHash = md5($surat->id . '-' . $nomorSurat . '-' . time());

        $surat->update([
            'status' => 'approved',
            'nomor_surat' => $nomorSurat,
            'qr_code_hash' => $qrCodeHash,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Surat berhasil disetujui & diterbitkan dengan Nomor: ' . $nomorSurat);
    }

    /**
     * Admin menolak pengajuan surat.
     */
    public function reject(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role === 'dosen') {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat menolak pengajuan ini.');
        }

        $isAdmin = session('admin_logged_in') 
            || (auth()->check() && in_array(auth()->user()->role, ['admin', 'admin_lppm'])) 
            || in_array(session('user_role'), ['admin', 'admin_lppm']);

        if (!$isAdmin) {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat menolak pengajuan ini.');
        }

        $surat = Surat::findOrFail($id);
        $alasan = $request->input('alasan_penolakan');

        $surat->update([
            'status' => 'rejected',
        ]);

        $pesan = 'Pengajuan surat ' . $surat->jenis_surat . ' (' . $surat->nama_dosen . ') telah ditolak.';
        if (!empty($alasan)) {
            $pesan .= ' Catatan: ' . $alasan;
        }

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * Generate PDF menggunakan DomPDF dan sisipkan QR Code base64 SVG dari URL verifikasi.
     */
    public function pdf($id)
    {
        $surat = Surat::findOrFail($id);

        // Jika belum disetujui, buat hash simulasi sementara agar tetap bisa di-preview
        if (empty($surat->qr_code_hash)) {
            $surat->qr_code_hash = md5($surat->id . '-preview-' . time());
        }

        $verifyUrl = route('surat.verify', ['hash' => $surat->qr_code_hash]);

        // Generate QR Code berformat SVG beresolusi tinggi lalu ubah ke Base64
        $qrSvg = QrCode::format('svg')
            ->size(130)
            ->errorCorrection('H')
            ->generate($verifyUrl);
        $qrCodeBase64 = base64_encode($qrSvg);

        // Load logo Mahardika sebagai Base64 agar dapat di-render tanpa dependensi HTTP
        $logoPath = public_path('mahardika-logo.png');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('surat.pdf', compact('surat', 'qrCodeBase64', 'verifyUrl', 'logoBase64'));
        $pdf->setPaper('a4', 'portrait');

        $cleanNomor = str_replace(['/', '\\'], '_', $surat->nomor_surat ?? ('ID_' . $surat->id));
        return $pdf->stream('Surat_' . $cleanNomor . '.pdf');
    }

    /**
     * Halaman publik untuk mengecek keaslian QR code TTD dan validitas dokumen.
     */
    public function verify($hash)
    {
        $surat = Surat::where('qr_code_hash', $hash)->first();

        return view('surat.verify', compact('surat', 'hash'));
    }

    /**
     * Hapus riwayat surat berdasarkan ID (khusus Admin).
     */
    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->delete();

        return redirect()->back()->with('success', 'Riwayat surat berhasil dihapus.');
    }

    /**
     * Tampilan cetak E-Surat langsung berbasis HTML/CSS A4 persis template Word.
     */
    public function cetak($id)
    {
        $surat = Surat::findOrFail($id);

        if (empty($surat->qr_code_hash)) {
            $surat->qr_code_hash = md5($surat->id . '-preview-' . time());
        }

        $verifyUrl = route('surat.verify', ['hash' => $surat->qr_code_hash]);

        return view('surat.cetak', compact('surat', 'verifyUrl'));
    }
}
