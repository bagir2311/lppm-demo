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
        $lecturers = Cache::remember('list_dosen_publik', 3600, function () {
            $dbLecturers = Lecturer::where('is_active', true)
                ->where('status', 'published')
                ->latest()
                ->get();

            if ($dbLecturers->isNotEmpty()) {
                return $dbLecturers->toArray();
            }

            return LPPMDataManager::getLecturers();
        });

        $settings = LPPMDataManager::getSettings();

        return view('frontend.dosen.index', compact('lecturers', 'settings'));
    }
}
