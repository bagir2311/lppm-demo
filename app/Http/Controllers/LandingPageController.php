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
        $validProdis = ['Kebidanan', 'Keperawatan', 'Rekam Medis', 'Kesmas', 'Informatika'];
        
        $statistics = LPPMDataManager::getStatistics();
        $announcements = LPPMDataManager::getAnnouncements();
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

        if (is_array($lecturer)) {
            $dbDosen = Lecturer::where('slug', $slug)->first();
            if ($dbDosen) {
                $lecturer['publications'] = $dbDosen->publications;
                $lecturer['citations'] = $dbDosen->citations;
            } else {
                $lecturer['publications'] = $lecturer['publications'] ?? '0';
                $lecturer['citations'] = $lecturer['citations'] ?? '0';
            }
        }

        $sintaRaw = is_array($lecturer) ? ($lecturer['sinta_url'] ?? null) : ($lecturer->sinta_url ?? null);
        $scholarRaw = is_array($lecturer) ? ($lecturer['scholar_url'] ?? null) : ($lecturer->scholar_url ?? null);

        $sintaUrl = self::formatSintaUrl($sintaRaw);
        $scholarUrl = self::formatScholarUrl($scholarRaw);

        $settings = LPPMDataManager::getSettings();

        return view('landing.lecturer-profile', compact('lecturer', 'settings', 'sintaUrl', 'scholarUrl'));
    }

    public static function formatSintaUrl(?string $raw): ?string
    {
        if (empty($raw) || trim($raw) === '' || trim($raw) === '#' || str_contains($raw, 'mock')) {
            return null;
        }
        $raw = trim($raw);

        // Jika nilai sinta_url sudah diawali http:// atau https://, gunakan URL tersebut secara utuh apa adanya
        // tanpa memotong query string (?q=...) maupun path-nya.
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }

        // Hanya jika inputan admin berupa angka murni (misal 6704887), ubah otomatis menjadi https://sinta.kemdiktisaintek.go.id/authors/profile/6704887
        if (preg_match('/^\d+$/', $raw)) {
            return 'https://sinta.kemdiktisaintek.go.id/authors/profile/' . $raw;
        }

        if (str_contains($raw, 'sinta.')) {
            return 'https://' . ltrim($raw, '/');
        }

        return $raw;
    }

    public static function formatScholarUrl(?string $raw): ?string
    {
        if (empty($raw) || trim($raw) === '' || trim($raw) === '#' || str_contains($raw, 'mock')) {
            return null;
        }
        $raw = trim($raw);
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }
        if (str_contains($raw, 'scholar.google.')) {
            return 'https://' . ltrim($raw, '/');
        }
        return 'https://scholar.google.com/citations?user=' . urlencode($raw);
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