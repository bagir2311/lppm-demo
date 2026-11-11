<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Proposal;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPenelitianController extends Controller
{
    private function authCheck()
    {
        if (Auth::check() && Auth::user()->role === 'dosen') {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat mengakses halaman ini.');
        }

        if (!Auth::check() && !session()->has('admin_logged_in')) {
            redirect()->route('admin.login')->send();
            exit;
        }
    }

    /**
     * Menampilkan meja kerja usulan proposal skema Penelitian.
     */
    public function index()
    {
        $this->authCheck();

        $proposals = Proposal::with(['dosen', 'reviewer'])
            ->where('skema', 'Penelitian')
            ->orderBy('created_at', 'desc')
            ->get();

        $reviewers = User::where('role', 'reviewer')->get();

        return view('admin.penelitian.index', compact('proposals', 'reviewers'));
    }

    /**
     * Admin melakukan plotting (assign) proposal ke Reviewer.
     */
    public function assignReviewer(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role === 'dosen') {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat menugaskan reviewer.');
        }

        $this->authCheck();

        $request->validate([
            'reviewer_id' => 'required|exists:users,id',
        ]);

        $proposal = Proposal::findOrFail($id);
        $proposal->update([
            'reviewer_id' => $request->reviewer_id,
            'status' => 'in_review',
        ]);

        return redirect()->back()->with('success', 'Reviewer berhasil ditugaskan.');
    }

    /**
     * Admin menyetujui usulan proposal penelitian.
     */
    public function approve($id)
    {
        if (auth()->check() && auth()->user()->role === 'dosen') {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat menyetujui dokumen ini.');
        }

        $this->authCheck();

        $proposal = Proposal::findOrFail($id);
        $proposal->update([
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Berhasil disetujui.');
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
