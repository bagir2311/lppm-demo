<?php

namespace App\Http\Controllers;

use App\Models\PenugasanReview;
use App\Models\Proposal;
use App\Models\Dosen;
use App\Models\PenilaianReviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewerController extends Controller
{
    /**
     * Resolusi ID Reviewer (Dosen) yang sedang aktif / login.
     * Mendukung Auth guard, Keycloak SSO / simulasi sesi, dan fallback ID Dosen reviewer.
     */
    protected function getActiveReviewerId(?Request $request = null): ?int
    {
        // 1. Dari Auth Laravel (User login)
        if (Auth::check()) {
            $user = Auth::user();
            if (!empty($user->dosen_id)) {
                return (int) $user->dosen_id;
            }
            if (method_exists($user, 'dosen') && $user->dosen) {
                return (int) $user->dosen->id;
            }
            return (int) $user->id;
        }

        // 2. Dari Session (SSO Keycloak atau impersonation)
        if (session()->has('reviewer_id')) {
            return (int) session('reviewer_id');
        }
        if (session()->has('dosen_id')) {
            return (int) session('dosen_id');
        }
        if (session()->has('user_id')) {
            return (int) session('user_id');
        }

        // 3. Fallback dari query parameter request jika ada
        if ($request && $request->filled('reviewer_id')) {
            return (int) $request->input('reviewer_id');
        }

        // 4. Default: ambil Dosen pertama yang berstatus is_reviewer = true, atau ID 1
        return Dosen::where('is_reviewer', true)->value('id') ?? 1;
    }

    /**
     * Menampilkan daftar proposal yang hanya ditugaskan kepada Reviewer yang sedang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        $reviewerId = $this->getActiveReviewerId($request);

        // Ambil hanya proposal yang ditugaskan ke reviewer ini
        $penugasanReviews = PenugasanReview::with(['proposal.dosen', 'reviewer'])
            ->where('reviewer_id', $reviewerId)
            ->latest('updated_at')
            ->get();

        $totalPenugasan = $penugasanReviews->count();
        $pendingCount = $penugasanReviews->where('status_penilaian', 'pending')->count();
        $revisiCount = $penugasanReviews->where('status_penilaian', 'revisi')->count();
        $diterimaCount = $penugasanReviews->where('status_penilaian', 'diterima')->count();
        $ditolakCount = $penugasanReviews->where('status_penilaian', 'ditolak')->count();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'reviewer_id' => $reviewerId,
                'data' => $penugasanReviews,
                'summary' => [
                     'total' => $totalPenugasan,
                     'pending' => $pendingCount,
                     'revisi' => $revisiCount,
                     'diterima' => $diterimaCount,
                     'ditolak' => $ditolakCount,
                ],
            ]);
        }

        $reviewer = Dosen::find($reviewerId);

        return view('reviewer.index', compact(
            'penugasanReviews',
            'reviewerId',
            'reviewer',
            'totalPenugasan',
            'pendingCount',
            'revisiCount',
            'diterimaCount',
            'ditolakCount'
        ));
    }

    /**
     * Menyimpan penugasan review baru atau evaluasi proposal awal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $reviewerId = $this->getActiveReviewerId($request);

        $validated = $request->validate([
            'proposal_id' => ['required', 'exists:proposals,id'],
            'reviewer_id' => ['nullable', 'exists:dosens,id'],
            'nilai' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'catatan_revisi' => ['nullable', 'string'],
            'status_penilaian' => ['required', Rule::in(['pending', 'revisi', 'diterima', 'ditolak'])],
        ]);

        $targetReviewerId = $validated['reviewer_id'] ?? $reviewerId;

        // Buat atau perbarui penugasan review
        $penugasanReview = PenugasanReview::updateOrCreate(
            [
                'proposal_id' => $validated['proposal_id'],
                'reviewer_id' => $targetReviewerId,
            ],
            [
                'nilai' => $validated['nilai'] ?? null,
                'catatan_revisi' => $validated['catatan_revisi'] ?? null,
                'status_penilaian' => $validated['status_penilaian'],
            ]
        );

        // Sinkronisasi status pada model proposal
        if ($penugasanReview->proposal) {
            $penugasanReview->proposal->update([
                'status' => $validated['status_penilaian'],
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Penugasan review proposal berhasil disimpan.',
                'data' => $penugasanReview->load(['proposal', 'reviewer']),
            ], 201);
        }

        return redirect()->back()->with('success', 'Penugasan review proposal berhasil disimpan.');
    }

    /**
     * Menyimpan hasil nilai, catatan revisi, dan mengubah status di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int|string $id): RedirectResponse|JsonResponse
    {
        $penugasanReview = PenugasanReview::with('proposal')->findOrFail($id);

        $validated = $request->validate([
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_revisi' => ['nullable', 'string'],
            'status_penilaian' => ['required', Rule::in(['pending', 'revisi', 'diterima', 'ditolak'])],
        ]);

        // Simpan hasil nilai, catatan revisi, dan status penilaian
        $penugasanReview->update([
            'nilai' => $validated['nilai'],
            'catatan_revisi' => $validated['catatan_revisi'] ?? null,
            'status_penilaian' => $validated['status_penilaian'],
        ]);

        // Update status proposal terkait jika ada
        if ($penugasanReview->proposal) {
            $penugasanReview->proposal->update([
                'status' => $validated['status_penilaian'],
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Hasil penilaian dan catatan revisi berhasil diperbarui.',
                'data' => $penugasanReview->fresh(['proposal', 'reviewer']),
            ]);
        }

        return redirect()->back()->with('success', 'Hasil penilaian berhasil disimpan dengan status: ' . ucfirst($validated['status_penilaian']));
    }

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

        $penilaians = collect();
        $totalPenilaian = 0;
        $pendingPenilaian = 0;
        $completedPenilaian = 0;

        return view('reviewer.dashboard', compact(
            'proposals',
            'totalProposal',
            'pendingProposal',
            'completedProposal',
            'penilaians',
            'totalPenilaian',
            'pendingPenilaian',
            'completedPenilaian'
        ));
    }

    /**
     * Legacy Form Penilaian
     */
    public function penilaian($id)
    {
        $penilaian = PenilaianReviewer::with(['penelitian.ketuaDosen', 'pengabdian.ketuaDosen'])->findOrFail($id);
        return view('reviewer.penilaian', compact('penilaian'));
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
