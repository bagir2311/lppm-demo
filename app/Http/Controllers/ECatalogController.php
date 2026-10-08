<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Http\Request;

class ECatalogController extends Controller
{
    /**
     * Display public E-Catalog with filters & search.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // 'all', 'penelitian', 'pengabdian'
        $search = $request->get('search');
        $tahun = $request->get('tahun');
        $skema = $request->get('skema');
        $bidang = $request->get('bidang');

        // Query Penelitian
        $penelitianQuery = Penelitian::with('ketuaDosen');
        if ($search) {
            $penelitianQuery->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('abstrak', 'like', "%{$search}%")
                  ->orWhereHas('ketuaDosen', function ($dosenQ) use ($search) {
                      $dosenQ->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        if ($tahun) {
            $penelitianQuery->where('tahun_pelaksanaan', $tahun);
        }
        if ($skema) {
            $penelitianQuery->where('skema', $skema);
        }
        if ($bidang) {
            $penelitianQuery->where('bidang_fokus', $bidang);
        }

        // Query Pengabdian
        $pengabdianQuery = Pengabdian::with('ketuaDosen');
        if ($search) {
            $pengabdianQuery->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('abstrak', 'like', "%{$search}%")
                  ->orWhereHas('ketuaDosen', function ($dosenQ) use ($search) {
                      $dosenQ->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        if ($tahun) {
            $pengabdianQuery->where('tahun_pelaksanaan', $tahun);
        }
        if ($skema) {
            $pengabdianQuery->where('skema', $skema);
        }
        if ($bidang) {
            $pengabdianQuery->where('bidang_fokus', $bidang);
        }

        $penelitians = collect();
        $pengabdians = collect();

        if ($type === 'all' || $type === 'penelitian') {
            $penelitians = $penelitianQuery->orderBy('tahun_pelaksanaan', 'desc')->get();
        }
        if ($type === 'all' || $type === 'pengabdian') {
            $pengabdians = $pengabdianQuery->orderBy('tahun_pelaksanaan', 'desc')->get();
        }

        // Available filter options
        $availableYears = Penelitian::select('tahun_pelaksanaan')
            ->union(Pengabdian::select('tahun_pelaksanaan'))
            ->distinct()
            ->orderBy('tahun_pelaksanaan', 'desc')
            ->pluck('tahun_pelaksanaan');

        $availableSkemas = Penelitian::select('skema')
            ->union(Pengabdian::select('skema'))
            ->distinct()
            ->pluck('skema');

        $availableBidang = Penelitian::select('bidang_fokus')
            ->union(Pengabdian::select('bidang_fokus'))
            ->distinct()
            ->pluck('bidang_fokus');

        $totalPenelitianCount = Penelitian::count();
        $totalPengabdianCount = Pengabdian::count();

        return view('e-catalog.index', compact(
            'penelitians',
            'pengabdians',
            'type',
            'search',
            'tahun',
            'skema',
            'bidang',
            'availableYears',
            'availableSkemas',
            'availableBidang',
            'totalPenelitianCount',
            'totalPengabdianCount'
        ));
    }

    /**
     * Show single Penelitian detail.
     */
    public function showPenelitian($id)
    {
        $item = Penelitian::with('ketuaDosen')->findOrFail($id);
        $typeLabel = 'Penelitian';

        // Related items from same lecturer or same field
        $relatedItems = Penelitian::with('ketuaDosen')
            ->where('id', '!=', $id)
            ->where('bidang_fokus', $item->bidang_fokus)
            ->limit(3)
            ->get();

        return view('e-catalog.show', compact('item', 'typeLabel', 'relatedItems'));
    }

    /**
     * Show single Pengabdian detail.
     */
    public function showPengabdian($id)
    {
        $item = Pengabdian::with('ketuaDosen')->findOrFail($id);
        $typeLabel = 'Pengabdian';

        $relatedItems = Pengabdian::with('ketuaDosen')
            ->where('id', '!=', $id)
            ->where('bidang_fokus', $item->bidang_fokus)
            ->limit(3)
            ->get();

        return view('e-catalog.show', compact('item', 'typeLabel', 'relatedItems'));
    }

    /**
     * Dashboard Manajemen Hibah (Pengajuan Proposal Dosen).
     */
    public function hibah()
    {
        // Data proposal penelitian & berkas untuk dashboard manajemen hibah
        $proposals = Penelitian::with('ketuaDosen')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPengajuan = $proposals->count();
        $menungguReview = $proposals->whereIn('status', ['Diajukan', 'Review', 'Pending'])->count();
        $disetujui = $proposals->whereIn('status', ['Didanai', 'Disetujui'])->count();

        return view('hibah', compact('proposals', 'totalPengajuan', 'menungguReview', 'disetujui'));
    }
}
