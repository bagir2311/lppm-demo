<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

class ReviewerController extends Controller
{
    /**
     * Dashboard untuk Reviewer yang login.
     */
    public function dashboard()
    {
        $proposals = Proposal::with(['dosen', 'periode'])
            ->where('reviewer_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $totalProposal = $proposals->count();
        $pendingProposal = $proposals->where('status', 'in_review')->count();
        $completedProposal = $proposals->whereIn('status', ['approved', 'rejected'])->count();

        return view('reviewer.dashboard', compact('proposals', 'totalProposal', 'pendingProposal', 'completedProposal'));
    }

    /**
     * Simpan evaluasi & scoring proposal oleh Reviewer.
     */
    public function storePenilaian(Request $request, $id)
    {
        $request->validate([
            'nilai_total' => 'required|numeric|min:0|max:100',
            'rekomendasi' => 'required|in:Diterima,Ditolak,Revisi',
            'catatan_reviewer' => 'nullable|string',
        ]);

        $proposal = Proposal::where('reviewer_id', auth()->id())->findOrFail($id);

        $status = match ($request->rekomendasi) {
            'Diterima' => 'approved',
            'Ditolak' => 'rejected',
            'Revisi' => 'in_review',
            default => 'in_review',
        };

        $proposal->update([
            'nilai_total' => $request->nilai_total,
            'rekomendasi' => $request->rekomendasi,
            'catatan_reviewer' => $request->catatan_reviewer,
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Penilaian proposal berhasil disimpan.');
    }
}
