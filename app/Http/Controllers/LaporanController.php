<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Proposal;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * Tampilkan formulir unggah LPJ / Pelaporan (Fase 70% atau 100%).
     */
    public function create(Request $request)
    {
        $proposalId = $request->query('proposal_id');
        $proposal = null;
        if ($proposalId) {
            $proposal = Proposal::with('dosen')->find($proposalId);
        }

        // Ambil daftar proposal didanai milik dosen ini untuk pilihan jika tidak ada proposal_id
        $user = Auth::user();
        $dosen = $user ? Dosen::where('email', $user->email)->orWhere('nama', $user->name)->first() : null;
        $dosenId = $dosen->id ?? ($user->id ?? 1);

        $proposalsDidanai = Proposal::where('dosen_id', $dosenId)
            ->whereIn('status', ['Didanai', 'approved', 'diterima', 'Diterima'])
            ->latest()
            ->get();

        if ($proposalsDidanai->isEmpty()) {
            $proposalsDidanai = Proposal::latest()->get();
        }

        return view('dosen.laporan.create', compact('proposal', 'proposalsDidanai', 'dosen'));
    }

    /**
     * Simpan unggahan berkas LPJ / Laporan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:proposals,id',
            'fase_pelaporan' => 'required|in:70%,100%',
            'file_laporan_substansi' => 'required|file|mimes:pdf,doc,docx|max:15360',
            'file_laporan_keuangan' => 'required|file|mimes:pdf,xls,xlsx|max:15360',
            'file_dokumentasi' => 'required|file|mimes:pdf,zip,rar,jpg,jpeg,png|max:20480',
            'pilihan_luaran' => 'nullable|required_if:fase_pelaporan,100%|in:Artikel,Berita di Koran,HKI,Video YouTube',
            'url_luaran' => 'nullable|string|max:500',
            'file_luaran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ], [
            'proposal_id.required' => 'Pilih kegiatan proposal yang dilaporkan.',
            'fase_pelaporan.required' => 'Pilih tahapan fase pelaporan (70% atau 100%).',
            'file_laporan_substansi.required' => 'File Laporan Substansi wajib diunggah.',
            'file_laporan_keuangan.required' => 'File Laporan Keuangan wajib diunggah.',
            'file_dokumentasi.required' => 'File Dokumentasi Kegiatan wajib diunggah.',
            'pilihan_luaran.required_if' => 'Untuk pelaporan 100%, Pilihan Luaran wajib ditentukan.',
        ]);

        $proposal = Proposal::findOrFail($request->proposal_id);
        $dosenId = $proposal->dosen_id ?? (Auth::user()?->dosen_id ?? 1);

        $time = time();
        $pathSubstansi = Storage::disk('public')->putFileAs('laporan_uploads', $request->file('file_laporan_substansi'), $time . '_substansi_' . $request->file('file_laporan_substansi')->getClientOriginalName());
        $pathKeuangan = Storage::disk('public')->putFileAs('laporan_uploads', $request->file('file_laporan_keuangan'), $time . '_keuangan_' . $request->file('file_laporan_keuangan')->getClientOriginalName());
        $pathDokumentasi = Storage::disk('public')->putFileAs('laporan_uploads', $request->file('file_dokumentasi'), $time . '_dokumentasi_' . $request->file('file_dokumentasi')->getClientOriginalName());

        $pathLuaran = null;
        if ($request->hasFile('file_luaran')) {
            $pathLuaran = Storage::disk('public')->putFileAs('laporan_uploads', $request->file('file_luaran'), $time . '_luaran_' . $request->file('file_luaran')->getClientOriginalName());
            $pathLuaran = 'storage/' . $pathLuaran;
        }

        Laporan::create([
            'proposal_id' => $proposal->id,
            'dosen_id' => $dosenId,
            'fase_pelaporan' => $request->fase_pelaporan,
            'file_laporan_substansi' => 'storage/' . $pathSubstansi,
            'file_laporan_keuangan' => 'storage/' . $pathKeuangan,
            'file_dokumentasi' => 'storage/' . $pathDokumentasi,
            'pilihan_luaran' => $request->fase_pelaporan === '100%' ? $request->pilihan_luaran : null,
            'url_luaran' => $request->fase_pelaporan === '100%' ? $request->url_luaran : null,
            'file_luaran' => $pathLuaran,
            'status' => 'Submitted',
        ]);

        $menuTab = strtolower($proposal->skema) === 'pengabdian' ? 'pengabdian' : 'penelitian';

        return redirect()->route('dosen.dashboard', ['tab' => $menuTab])->with('success', 'Laporan Pertanggungjawaban (LPJ ' . $request->fase_pelaporan . ') berhasil diunggah!');
    }
}
