<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Models\Dosen;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        $surats = SuratPengajuan::with(['user', 'dosen'])->orderBy('created_at', 'desc')->get();
        $dosens = Dosen::orderBy('nama', 'asc')->get();

        return view('surat.index', compact('surats', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'judul_penelitian' => 'required',
            'lokasi_penelitian' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        $dosenId = $request->dosen_id ?? Dosen::first()->id;

        SuratPengajuan::create([
            'user_id' => session('user_id', 1),
            'dosen_id' => $dosenId,
            'jenis_surat' => $request->jenis_surat,
            'judul_penelitian' => $request->judul_penelitian,
            'lokasi_penelitian' => $request->lokasi_penelitian,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan surat berhasil dikirim! Menunggu verifikasi admin LPPM.');
    }

    public function approve(Request $request, $id)
    {
        $surat = SuratPengajuan::findOrFail($id);
        
        $nomorGenerated = 'B/' . rand(100, 999) . '/LPPM-UM/III/' . date('Y');

        $surat->update([
            'status' => 'Disetujui',
            'nomor_surat' => $nomorGenerated,
            'catatan_admin' => $request->catatan_admin ?? 'Surat pengajuan telah disetujui dan diverifikasi oleh LPPM.',
        ]);

        return redirect()->back()->with('success', 'Surat berhasil disetujui dengan Nomor: ' . $nomorGenerated);
    }

    public function pdf($id)
    {
        $surat = SuratPengajuan::with(['user', 'dosen'])->findOrFail($id);
        return view('surat.pdf', compact('surat'));
    }
}
