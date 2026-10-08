<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Dosen;
use Illuminate\Http\Request;

class AdminPenelitianController extends Controller
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

        $penelitians = Penelitian::with('ketuaDosen')->orderBy('tahun_pelaksanaan', 'desc')->get();
        $dosens = Dosen::orderBy('nama', 'asc')->get();

        return view('admin.penelitian', compact('penelitians', 'dosens'));
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

        Penelitian::create([
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'tahun_pelaksanaan' => $request->tahun_pelaksanaan,
            'skema' => $request->skema,
            'bidang_fokus' => $request->bidang_fokus,
            'ketua_dosen_id' => $request->ketua_dosen_id,
            'status' => $request->status,
            'dana_disetujui' => $request->dana_disetujui ?? 0,
            'file_pdf_url' => $request->file_pdf_url,
        ]);

        return redirect()->back()->with('success', 'Data Penelitian berhasil ditambahkan!');
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

        $penelitian = Penelitian::findOrFail($id);
        $penelitian->update([
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'tahun_pelaksanaan' => $request->tahun_pelaksanaan,
            'skema' => $request->skema,
            'bidang_fokus' => $request->bidang_fokus,
            'ketua_dosen_id' => $request->ketua_dosen_id,
            'status' => $request->status,
            'dana_disetujui' => $request->dana_disetujui ?? 0,
            'file_pdf_url' => $request->file_pdf_url,
        ]);

        return redirect()->back()->with('success', 'Data Penelitian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->authCheck();

        $penelitian = Penelitian::findOrFail($id);
        $penelitian->delete();

        return redirect()->back()->with('success', 'Data Penelitian berhasil dihapus.');
    }
}
