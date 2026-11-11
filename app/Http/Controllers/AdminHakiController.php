<?php

namespace App\Http\Controllers;

use App\Models\HakiSubmission;
use Illuminate\Http\Request;

class AdminHakiController extends Controller
{
    /**
     * Tampilkan meja kerja admin untuk verifikasi pengajuan HKI dosen.
     */
    public function index()
    {
        $hakiSubmissions = HakiSubmission::with('dosen')->orderBy('created_at', 'desc')->get();
        $totalHki = $hakiSubmissions->count();
        $pendingHki = $hakiSubmissions->filter(function ($item) {
            return !in_array(strtolower($item->status), ['disetujui', 'approved', 'ditolak', 'rejected']);
        })->count();
        $approvedHki = $hakiSubmissions->filter(function ($item) {
            return in_array(strtolower($item->status), ['disetujui', 'approved']);
        })->count();
        $rejectedHki = $hakiSubmissions->filter(function ($item) {
            return in_array(strtolower($item->status), ['ditolak', 'rejected']);
        })->count();

        return view('admin.hki.index', compact('hakiSubmissions', 'totalHki', 'pendingHki', 'approvedHki', 'rejectedHki'));
    }

    /**
     * Setujui pengajuan HKI dosen dan terbitkan nomor pencatatan.
     */
    public function approve(Request $request, $id)
    {
        $hki = HakiSubmission::findOrFail($id);

        $nomorPencatatan = $request->input('nomor_pencatatan') ?? ('EC' . date('Y') . sprintf('%04d', $hki->id));

        $hki->update([
            'status' => 'Disetujui',
            'nomor_pencatatan' => $nomorPencatatan,
            'catatan_admin' => $request->input('catatan_admin', 'Pengajuan HKI telah diverifikasi dan disetujui oleh Sentra HKI LPPM.')
        ]);

        return redirect()->back()->with('success', 'Pengajuan HKI "' . $hki->judul_ciptaan . '" berhasil disetujui!');
    }

    /**
     * Tolak pengajuan HKI dosen dengan catatan perbaikan.
     */
    public function reject(Request $request, $id)
    {
        $hki = HakiSubmission::findOrFail($id);

        $hki->update([
            'status' => 'Ditolak',
            'catatan_admin' => $request->input('catatan_admin', 'Pengajuan HKI ditolak atau berkas belum memenuhi persyaratan.')
        ]);

        return redirect()->back()->with('success', 'Pengajuan HKI "' . $hki->judul_ciptaan . '" telah ditolak dengan catatan.');
    }
}
