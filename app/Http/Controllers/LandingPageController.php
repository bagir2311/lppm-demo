<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LPPMDataManager;

class LandingPageController extends Controller
{
    /**
     * Display the LPPM Mahardika landing page using the live session data manager.
     */
    public function index()
    {
        $statistics = LPPMDataManager::getStatistics();
        $announcements = LPPMDataManager::getAnnouncements();
        $lecturers = LPPMDataManager::getLecturers();
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
        $lecturers = LPPMDataManager::getLecturers();
        $lecturer = null;

        foreach ($lecturers as $lec) {
            if ($lec['slug'] === $slug) {
                $lecturer = $lec;
                break;
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
        $keycloakUrl = config('services.keycloak.base_url', 'https://sso.mahardika.ac.id') 
            . '/realms/' . config('services.keycloak.realm', 'mahardika') 
            . '/protocol/openid-connect/auth';

        $clientId = config('services.keycloak.client_id', 'lppm-landingpage');
        $redirectUri = config('services.keycloak.redirect_uri', 'https://lppm.mahardika.ac.id/portal/callback');

        // Temporary target URL specified in the requirements
        $ssoRedirectTarget = 'https://sso.mahardika.ac.id/realms/mahardika/protocol/openid-connect/auth?client_id=account&redirect_uri=https%3A%2F%2Fsso.mahardika.ac.id%2Frealms%2Fmahardika%2Faccount%2Flogin-redirect&state=0%2Fb1501b07-c3f5-1cf6-fbf8-0983cf40aa2f&response_type=code&scope=openid';

        return redirect()->away($ssoRedirectTarget);
    }

    /**
     * SSO Callback Placeholder receiver.
     */
    public function ssoCallback(Request $request)
    {
        // Simple simulation: log in the user using the SSO callback code
        session(['user_logged_in' => true]);
        session(['sso_token' => 'simulated_oauth2_sso_bearer_token_' . uniqid()]);
        
        return redirect()->route('portal.index')->with('success', 'SSO Login berhasil Terkoneksi Keycloak!');
    }

    /**
     * SSO / General Logout action.
     */
    public function ssoLogout(Request $request)
    {
        session()->forget('user_logged_in');
        session()->forget('sso_token');
        
        return redirect()->route('landing.index')->with('success', 'Berhasil logout dari sistem.');
    }
}
