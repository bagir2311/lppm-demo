<?php

namespace App\Http\Controllers;

use App\Models\HakiSubmission;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HakiController extends Controller
{
    /**
     * Tampilkan formulir permohonan pengajuan HKI baru.
     */
    public function create()
    {
        $dosen = null;
        if (Auth::check() && Auth::user()->dosen_id) {
            $dosen = Dosen::find(Auth::user()->dosen_id);
        } elseif (Auth::check()) {
            $dosen = Dosen::where('email', Auth::user()->email)->first();
        }
        if (!$dosen && class_exists(Dosen::class)) {
            $dosen = Dosen::first();
        }

        return view('dosen.haki.create', compact('dosen'));
    }

    /**
     * Simpan pengajuan HKI baru beserta array data pencipta dan berkas dokumen.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_ciptaan' => 'required|string|max:255',
            'jenis_ciptaan' => 'required|string|max:100',
            'pencipta' => 'nullable|array',
            'pencipta.*.nama' => 'nullable|string|max:255',
            'pencipta.*.nik' => 'nullable|string|max:50',
            'pencipta.*.alamat' => 'nullable|string|max:500',
            'pencipta.*.kodepos' => 'nullable|string|max:20',
            'pencipta.*.email' => 'nullable|string|max:100',
            'pencipta.*.nohp' => 'nullable|string|max:50',
            'file_surat_pengalihan' => 'nullable|file|mimes:pdf,doc,docx,zip|max:20480',
            'file_surat_pernyataan' => 'nullable|file|mimes:pdf,doc,docx,zip|max:20480',
            'file_draft_karya' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:30720',
            'file_lampiran_karya' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:30720',
            'file_ktp' => 'nullable|file|mimes:pdf,doc,docx,zip|max:20480',
            'file_bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
        ], [
            'judul_ciptaan.required' => 'Judul karya/ciptaan wajib diisi.',
            'jenis_ciptaan.required' => 'Jenis ciptaan wajib dipilih.',
            'file_surat_pengalihan.mimes' => 'Format file Surat Pengalihan harus PDF, DOC, DOCX, atau ZIP.',
            'file_surat_pernyataan.mimes' => 'Format file Surat Pernyataan harus PDF, DOC, DOCX, atau ZIP.',
            'file_draft_karya.mimes' => 'Format file Draft Karya harus PDF, DOC, DOCX, atau ZIP.',
            'file_lampiran_karya.mimes' => 'Format file Draft Karya harus PDF, DOC, DOCX, atau ZIP.',
        ]);

        $dosenId = $request->input('dosen_id');
        if (!$dosenId) {
            if (Auth::check() && Auth::user()->dosen_id) {
                $dosenId = Auth::user()->dosen_id;
            } elseif (Auth::check()) {
                $dosenId = Dosen::where('email', Auth::user()->email)->first()?->id;
            }
        }
        if (!$dosenId && class_exists(Dosen::class)) {
            $dosenId = Dosen::first()?->id ?? 1;
        }

        // Ekstraksi data pencipta
        $penciptaList = $request->input('pencipta', []);
        $namaKetua = $request->input('nama_ketua');
        $namaAnggota = $request->input('nama_anggota');

        if (is_array($penciptaList) && count($penciptaList) > 0) {
            if (empty($namaKetua) && !empty($penciptaList[0]['nama'])) {
                $namaKetua = $penciptaList[0]['nama'];
            }
            $anggotaArray = [];
            foreach (array_slice($penciptaList, 1) as $p) {
                if (!empty($p['nama'])) {
                    $anggotaArray[] = $p['nama'] . (!empty($p['nik']) ? ' (NIK: ' . $p['nik'] . ')' : '');
                }
            }
            if (!empty($anggotaArray)) {
                $namaAnggota = implode(', ', $anggotaArray);
            }
        }

        if (empty($namaKetua)) {
            $namaKetua = Auth::check() ? Auth::user()->name : 'Ketua Pengusul';
        }

        $time = time();
        $pathPengalihan = null;
        if ($request->hasFile('file_surat_pengalihan')) {
            $pathPengalihan = 'storage/' . Storage::disk('public')->putFileAs('haki_uploads', $request->file('file_surat_pengalihan'), $time . '_pengalihan_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->file('file_surat_pengalihan')->getClientOriginalName()));
        }

        $pathPernyataan = null;
        if ($request->hasFile('file_surat_pernyataan')) {
            $pathPernyataan = 'storage/' . Storage::disk('public')->putFileAs('haki_uploads', $request->file('file_surat_pernyataan'), $time . '_pernyataan_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->file('file_surat_pernyataan')->getClientOriginalName()));
        }

        $fileKarya = $request->file('file_draft_karya') ?? $request->file('file_lampiran_karya');
        $pathKarya = null;
        if ($fileKarya) {
            $pathKarya = 'storage/' . Storage::disk('public')->putFileAs('haki_uploads', $fileKarya, $time . '_karya_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileKarya->getClientOriginalName()));
        }

        $pathKtp = null;
        if ($request->hasFile('file_ktp')) {
            $pathKtp = 'storage/' . Storage::disk('public')->putFileAs('haki_uploads', $request->file('file_ktp'), $time . '_ktp_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->file('file_ktp')->getClientOriginalName()));
        }

        $pathBayar = null;
        if ($request->hasFile('file_bukti_pembayaran')) {
            $pathBayar = 'storage/' . Storage::disk('public')->putFileAs('haki_uploads', $request->file('file_bukti_pembayaran'), $time . '_bayar_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->file('file_bukti_pembayaran')->getClientOriginalName()));
        }

        HakiSubmission::create([
            'dosen_id' => $dosenId,
            'user_id' => Auth::id() ?? 1,
            'judul_ciptaan' => $request->judul_ciptaan,
            'jenis_ciptaan' => $request->jenis_ciptaan,
            'nama_ketua' => $namaKetua,
            'nama_anggota' => $namaAnggota,
            'data_pencipta' => !empty($penciptaList) ? json_encode($penciptaList) : null,
            'file_surat_pengalihan' => $pathPengalihan,
            'file_surat_pernyataan' => $pathPernyataan,
            'file_lampiran_karya' => $pathKarya,
            'file_ktp' => $pathKtp,
            'file_bukti_pembayaran' => $pathBayar,
            'status' => 'Diajukan',
        ]);

        return redirect()->route('dosen.dashboard', ['tab' => 'haki'])->with('success', 'Permohonan HKI berhasil diajukan! Sentra HKI LPPM akan segera memeriksa kelengkapan berkas Anda.');
    }

    /**
     * Unduh file template resmi HKI (Data Pembuat, Pengalihan, Pernyataan).
     */
    public function downloadTemplate($type)
    {
        // Tentukan nama file berdasarkan parameter
        $filenames = [
            'data_pembuat' => 'Template DATA PEMBUAT KARYA.docx',
            'pengalihan' => file_exists(public_path('templates/Template SURAT PENGALIHAN HAK CIPTA.docx')) ? 'Template SURAT PENGALIHAN HAK CIPTA.docx' : 'Template SURAT PENGALIHAN HAK CIPTA.doc',
            'pernyataan' => 'TEMPLATE pernyataan HAK CIPTA.docx',
        ];

        if (!array_key_exists($type, $filenames)) {
            return back()->with('error', 'Jenis template tidak valid.');
        }

        $path = public_path('templates/' . $filenames[$type]);

        if (!file_exists($path)) {
            return back()->with('error', 'File template belum diunggah ke server (folder public/templates).');
        }

        return response()->download($path);
    }
}
