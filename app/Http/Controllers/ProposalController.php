<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Proposal;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    /**
     * Tampilkan formulir pengusulan proposal baru.
     * Memeriksa apakah gelombang pengajuan (periode aktif) sedang dibuka.
     */
    public function create()
    {
        $today = now()->toDateString();

        // Cek apakah ada record di tabel periodes yang status 'aktif' DAN hari ini <= tanggal_tutup
        $periodeAktif = Periode::where('status', 'aktif')
            ->whereDate('tanggal_tutup', '>=', $today)
            ->first();

        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Gelombang pengajuan belum dibuka atau sudah ditutup.');
        }

        // Ambil data profil dosen pengusul
        $dosen = null;
        if (Auth::check() && Auth::user()->dosen_id) {
            $dosen = Dosen::find(Auth::user()->dosen_id);
        } elseif (Auth::check()) {
            $dosen = Dosen::where('email', Auth::user()->email)->first();
        }

        if (!$dosen) {
            $dosen = Dosen::first();
        }

        return view('dosen.proposal.create', compact('periodeAktif', 'dosen'));
    }

    /**
     * Simpan usulan proposal baru beserta dokumen pendukung ke penyimpanan.
     */
    public function store(Request $request)
    {
        $today = now()->toDateString();

        // Validasi periode aktif kembali saat data disubmit
        $periodeAktif = Periode::where('status', 'aktif')
            ->whereDate('tanggal_tutup', '>=', $today)
            ->first();

        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Gelombang pengajuan belum dibuka atau sudah ditutup.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'skema' => 'required|in:Penelitian,Pengabdian',
            'jenis_hibah' => 'nullable|string|max:100',
            'sub_kategori' => 'nullable|string|max:100',
            'file_proposal' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'file_rab' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'judul.required' => 'Judul usulan proposal wajib diisi.',
            'skema.required' => 'Pilih salah satu skema usulan (Penelitian atau Pengabdian).',
            'file_proposal.required' => 'File dokumen proposal wajib diunggah.',
            'file_proposal.mimes' => 'Format file proposal harus berupa PDF, DOC, atau DOCX.',
            'file_proposal.max' => 'Ukuran file proposal maksimal adalah 5 MB.',
            'file_rab.required' => 'File Rencana Anggaran Biaya (RAB) wajib diunggah.',
            'file_rab.mimes' => 'Format file RAB harus berupa PDF, DOC, atau DOCX.',
            'file_rab.max' => 'Ukuran file RAB maksimal adalah 5 MB.',
        ]);

        // Tentukan dosen ID pengusul
        $dosenId = $request->input('dosen_id');
        if (!$dosenId) {
            if (Auth::check() && Auth::user()->dosen_id) {
                $dosenId = Auth::user()->dosen_id;
            } elseif (Auth::check()) {
                $dosenId = Dosen::where('email', Auth::user()->email)->first()?->id;
            }
        }
        if (!$dosenId) {
            $dosenId = Dosen::first()?->id ?? 1;
        }

        // Simpan file ke storage 'public/proposals' menggunakan facade Storage
        $fileProposal = $request->file('file_proposal');
        $fileProposalName = time() . '_proposal_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileProposal->getClientOriginalName());
        $pathProposal = Storage::disk('public')->putFileAs('proposals', $fileProposal, $fileProposalName);

        $fileRab = $request->file('file_rab');
        $fileRabName = time() . '_rab_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileRab->getClientOriginalName());
        $pathRab = Storage::disk('public')->putFileAs('proposals', $fileRab, $fileRabName);

        // Simpan data teks form ke tabel proposals
        Proposal::create([
            'dosen_id' => $dosenId,
            'periode_id' => $periodeAktif->id,
            'judul' => $request->judul,
            'skema' => $request->skema,
            'jenis_hibah' => $request->input('jenis_hibah', 'Internal LPPM'),
            'sub_kategori' => $request->skema === 'Penelitian' ? $request->input('sub_kategori', 'Dasar') : null,
            'file_proposal' => $pathProposal,
            'file_rab' => $pathRab,
            'status' => 'Diajukan',
            'nilai_total' => null,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Usulan proposal berhasil diajukan ke LPPM! Status: Diajukan.');
    }
}
