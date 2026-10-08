<?php

namespace App\Http\Controllers;

use App\Models\PenilaianReviewer;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Http\Request;

class ReviewerController extends Controller
{
    public function dashboard()
    {
        $penilaians = PenilaianReviewer::with(['penelitian.ketuaDosen', 'pengabdian.ketuaDosen'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPenilaian = $penilaians->count();
        $pendingPenilaian = $penilaians->where('status', 'Belum Dinilai')->count();
        $completedPenilaian = $penilaians->where('status', 'Selesai')->count();

        return view('reviewer.dashboard', compact('penilaians', 'totalPenilaian', 'pendingPenilaian', 'completedPenilaian'));
    }

    public function penilaian($id)
    {
        $penilaian = PenilaianReviewer::with(['penelitian.ketuaDosen', 'pengabdian.ketuaDosen'])->findOrFail($id);
        return view('reviewer.penilaian', compact('penilaian'));
    }

    public function storePenilaian(Request $request, $id)
    {
        $request->validate([
            'skor_metodologi' => 'required|numeric|min:0|max:100',
            'skor_relevansi' => 'required|numeric|min:0|max:100',
            'skor_kelayakan' => 'required|numeric|min:0|max:100',
            'rekomendasi' => 'required',
        ]);

        $penilaian = PenilaianReviewer::findOrFail($id);
        
        $totalSkor = round(($request->skor_metodologi * 0.4) + ($request->skor_relevansi * 0.3) + ($request->skor_kelayakan * 0.3));

        $penilaian->update([
            'skor_metodologi' => $request->skor_metodologi,
            'skor_relevansi' => $request->skor_relevansi,
            'skor_kelayakan' => $request->skor_kelayakan,
            'total_skor' => $totalSkor,
            'rekomendasi' => $request->rekomendasi,
            'catatan_reviewer' => $request->catatan_reviewer,
            'status' => 'Selesai',
        ]);

        return redirect()->route('reviewer.dashboard')->with('success', 'Evaluasi proposal berhasil disimpan dengan Total Skor: ' . $totalSkor);
    }
}
