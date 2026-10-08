<?php

namespace App\Http\Controllers;

use App\Models\Pengabdian;
use App\Models\Dosen;
use Illuminate\Http\Request;

class AdminPengabdianController extends Controller
{
    private function authCheck()
    {
        if (!session()->has('admin_logged_in')) {
            redirect()->route('admin.login')->send();
            exit;
        }
    }

    public function index()
    {
        $this->authCheck();

        $pengabdians = Pengabdian::with('ketuaDosen')->orderBy('tahun_pelaksanaan', 'desc')->get();
        $dosens = Dosen::orderBy('nama', 'asc')->get();

        return view('admin.pengabdian', compact('pengabdians', 'dosens'));
    }

    public function store(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'judul' => 'required',
            'tahun_pelaksanaan' => 'required|numeric',
            'skema' => 'required',
            'bidang_fokus' => 'required',
            'ketua_dosen_id' => 'required|exists:dosens,id',
            'status' => 'required',
        ]);

        Pengabdian::create([
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'tahun_pelaksanaan' => $request->tahun_pelaksanaan,
            'skema' => $request->skema,
            'bidang_fokus' => $request->bidang_fokus,
            'ketua_dosen_id' => $request->ketua_dosen_id,
            'status' => $request->status,
            'dana_disetujui' => $request->dana_disetujui ?? 0,
            'lokasi_mitra' => $request->lokasi_mitra,
            'file_pdf_url' => $request->file_pdf_url,
        ]);

        return redirect()->back()->with('success', 'Data Pengabdian Masyarakat berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $this->authCheck();

        $request->validate([
            'judul' => 'required',
            'tahun_pelaksanaan' => 'required|numeric',
            'skema' => 'required',
            'bidang_fokus' => 'required',
            'ketua_dosen_id' => 'required|exists:dosens,id',
            'status' => 'required',
        ]);

        $pengabdian = Pengabdian::findOrFail($id);
        $pengabdian->update([
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'tahun_pelaksanaan' => $request->tahun_pelaksanaan,
            'skema' => $request->skema,
            'bidang_fokus' => $request->bidang_fokus,
            'ketua_dosen_id' => $request->ketua_dosen_id,
            'status' => $request->status,
            'dana_disetujui' => $request->dana_disetujui ?? 0,
            'lokasi_mitra' => $request->lokasi_mitra,
            'file_pdf_url' => $request->file_pdf_url,
        ]);

        return redirect()->back()->with('success', 'Data Pengabdian Masyarakat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->authCheck();

        $pengabdian = Pengabdian::findOrFail($id);
        $pengabdian->delete();

        return redirect()->back()->with('success', 'Data Pengabdian Masyarakat berhasil dihapus.');
    }
}
