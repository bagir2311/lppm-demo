<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LPPMDataManager;
use App\Models\Lecturer;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    /**
     * Display the LPPM Mahardika landing page using the live session data manager.
     */
    public function index()
    {
        $statistics = LPPMDataManager::getStatistics();
        $announcements = LPPMDataManager::getAnnouncements();
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
        $documents = LPPMDataManager::getDocuments();
        $portals = LPPMDataManager::getPortals();
        $timeline = LPPMDataManager::getTimeline();
        $settings = LPPMDataManager::getSettings();

        return view('landing.index', compact(
            'statistics',
            'announcements',
            'lecturers',
            'documents',
            'portals',
            'timeline',
            'settings'
        ));
    }

    /**
     * Display a detailed profile page for a single lecturer.
     */
    public function lecturerProfile($slug)
    {
        $lecturer = Lecturer::where('slug', $slug)->first();

        if (!$lecturer) {
            $lecturers = LPPMDataManager::getLecturers();
            foreach ($lecturers as $lec) {
                if ($lec['slug'] === $slug) {
                    $lecturer = $lec;
                    break;
                }
            }
        }

        if (!$lecturer) {
            abort(404, 'Dosen tidak ditemukan.');
        }

        $settings = LPPMDataManager::getSettings();

        return view('landing.lecturer-profile', compact('lecturer', 'settings'));
    }

    /**
     * Display the Portal Apps Mahardika Hub.
     */
    public function portal()
    {
        $settings = LPPMDataManager::getSettings();
        return view('portal.index', compact('settings'));
    }

    /**
     * SSO Keycloak Login Placeholder redirect.
     */
    public function ssoLogin()
    {
        // Temporary target URL specified in the requirements
        $ssoRedirectTarget = 'https://sso.mahardika.ac.id/realms/mahardika/protocol/openid-connect/auth?client_id=account&redirect_uri=https%3A%2F%2Fsso.mahardika.ac.id%2Frealms%2Fmahardika%2Faccount%2Flogin-redirect&state=0%2Fb1501b07-c3f5-1cf6-fbf8-0983cf40aa2f&response_type=code&scope=openid';

        return redirect()->away($ssoRedirectTarget);
    }

    /**
     * SSO Callback Placeholder receiver.
     */
    public function ssoCallback(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role === 'reviewer') {
                return redirect()->route('reviewer.dashboard');
            }
            if ($user->role === 'dosen') {
                return redirect()->route('dosen.dashboard');
            }
        }

        if (session('user_role') === 'reviewer') {
            return redirect()->route('reviewer.dashboard');
        }

        // Simulasi session sukses untuk dummy login
        session(['user_logged_in' => true]);
        session(['user_role' => 'dosen']); 

        return redirect()->route('dosen.dashboard');
    }

    /**
     * SSO / General Logout action.
     */
    public function ssoLogout(Request $request)
    {
        // Menghapus semua session login (baik dummy maupun otentikasi asli)
        auth()->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->forget('user_logged_in');
        session()->forget('admin_logged_in');
        session()->forget('user_role');
        session()->forget('sso_token');
        
        return redirect()->route('landing.index')->with('success', 'Berhasil logout dari sistem.');
    }
}