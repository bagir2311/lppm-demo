<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Helpers\LPPMDataManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DosenPublicController extends Controller
{
    /**
     * Tampilkan halaman Direktori Dosen & Peneliti publik.
     */
    public function index(Request $request)
    {
        $validProdis = ['Kebidanan', 'Keperawatan', 'Rekam Medis', 'Kesmas', 'Informatika'];

        $lecturers = Cache::remember('list_dosen_publik', 3600, function () use ($validProdis) {
            $dbLecturers = Lecturer::where('is_active', true)
                ->where('status', 'published')
                ->whereIn('prodi', $validProdis)
                ->orderBy('nama', 'asc')
                ->get();

            if ($dbLecturers->isNotEmpty()) {
                return $dbLecturers->toArray();
            }

            return array_values(array_filter(LPPMDataManager::getLecturers(), function ($l) use ($validProdis) {
                return in_array($l['study_program'] ?? ($l['prodi'] ?? ''), $validProdis);
            }));
        });

        $settings = LPPMDataManager::getSettings();

        return view('frontend.dosen.index', compact('lecturers', 'settings'));
    }
}
