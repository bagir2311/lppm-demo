<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LPPMDataManager;
use App\Models\BerkasPengajuan;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Lecturer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    private function isAuth()
    {
        return Auth::check();
    }

    private function authCheck()
    {
        if (!$this->isAuth() && !session('admin_logged_in') && !session('user_logged_in')) {
            redirect()->route('login')->send();
            exit;
        }

        $user = Auth::user();
        $allowedRoles = ['admin', 'admin_lppm', 'staf', 'staff'];
        $hasAccess = ($user && in_array($user->role, $allowedRoles))
            || session('admin_logged_in')
            || in_array(session('user_role'), $allowedRoles);

        if (!$hasAccess) {
            abort(403, 'Akses Terlarang. Panel ini hanya dapat diakses oleh Administrator dan Staf LPPM.');
        }
    }

   public function loginForm()
    {
        if ($this->isAuth()) {
            $role = strtolower(Auth::user()->role ?? '');
            if ($role === 'dosen') {
                return redirect()->route('dosen.dashboard');
            } elseif ($role === 'reviewer') {
                return redirect()->route('reviewer.dashboard');
            } elseif (in_array($role, ['admin', 'staf', 'staff', 'admin_lppm'])) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dosen.dashboard');
        }
        
        return view('admin.login');
    }

    public function loginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $role = strtolower(Auth::user()->role ?? '');
            if ($role === 'dosen') {
                return redirect()->route('dosen.dashboard')->with('success', 'Selamat datang di Dasbor Dosen LPPM Mahardika!');
            } elseif ($role === 'reviewer') {
                return redirect()->route('reviewer.dashboard')->with('success', 'Selamat datang di Dasbor Penilaian Reviewer!');
            } elseif (in_array($role, ['admin', 'staf', 'staff', 'admin_lppm'])) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang di Panel Admin LPPM!');
            } else {
                return redirect()->route('dosen.dashboard')->with('success', 'Selamat Datang di Portal LPPM!');
            }
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Kredensial login salah. Silakan periksa kembali email dan password Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('landing.index')->with('success', 'Logout berhasil.');
    }

    public function dashboard()
    {
        $this->authCheck();

        $announcements = LPPMDataManager::getAnnouncements();
        $lecturers = LPPMDataManager::getLecturers();
        $docs = LPPMDataManager::getDocuments();
        
        $totalAnnouncements = count($announcements);
        $totalLecturers = count($lecturers);
        $totalDocs = count($docs);
        
        $activePopups = 0;
        foreach ($announcements as $ann) {
            if (isset($ann['show_popup']) && $ann['show_popup'] === true) {
                $activePopups++;
            }
        }

        $settings = LPPMDataManager::getSettings();

        return view('admin.dashboard', compact(
            'totalAnnouncements', 'totalLecturers', 'totalDocs', 'activePopups', 'settings'
        ));
    }

    public function announcements()
    {
        $this->authCheck();
        $announcements = LPPMDataManager::getAnnouncements();
        $settings = LPPMDataManager::getSettings();
        return view('admin.announcements', compact('announcements', 'settings'));
    }

    public function announcementCreate(Request $request)
    {
        $this->authCheck();
        
        $request->validate([
            'title' => 'required', 'category' => 'required', 
            'category_type' => 'required', 'summary' => 'required',
        ]);

        $announcements = LPPMDataManager::getAnnouncements();
        $nextId = 1;
        foreach ($announcements as $ann) {
            if ($ann['id'] >= $nextId) $nextId = $ann['id'] + 1;
        }

        $newAnn = [
            'id' => $nextId, 'title' => $request->title, 'date' => date('d M Y'),
            'category' => $request->category, 'category_type' => $request->category_type,
            'summary' => $request->summary, 'is_important' => $request->category_type === 'important',
            'is_new' => true, 'file_size' => $request->file_size ?? '1.2 MB',
            'link' => $request->link ?? '#', 'show_popup' => $request->has('show_popup'),
            'start_date' => $request->start_date ?? date('Y-m-d'),
            'end_date' => $request->end_date ?? date('Y-m-d', strtotime('+30 days'))
        ];

        if ($newAnn['show_popup']) {
            foreach ($announcements as &$ann) $ann['show_popup'] = false;
        }

        $announcements[] = $newAnn;
        LPPMDataManager::saveAnnouncements($announcements);

        return redirect()->back()->with('success', 'Pengumuman baru berhasil dipublikasikan!');
    }

    public function announcementUpdate(Request $request, $id)
    {
        $this->authCheck();
        $request->validate([
            'title' => 'required', 'category' => 'required',
            'category_type' => 'required', 'summary' => 'required',
        ]);

        $announcements = LPPMDataManager::getAnnouncements();
        $found = false;

        foreach ($announcements as &$ann) {
            if ($ann['id'] == $id) {
                $ann['title'] = $request->title;
                $ann['category'] = $request->category;
                $ann['category_type'] = $request->category_type;
                $ann['summary'] = $request->summary;
                $ann['is_important'] = $request->category_type === 'important';
                $ann['file_size'] = $request->file_size ?? $ann['file_size'];
                $ann['link'] = $request->link ?? $ann['link'];
                $ann['show_popup'] = $request->has('show_popup');
                $ann['start_date'] = $request->start_date ?? $ann['start_date'];
                $ann['end_date'] = $request->end_date ?? $ann['end_date'];
                
                if ($ann['show_popup']) {
                    foreach ($announcements as &$other) {
                        if ($other['id'] != $id) $other['show_popup'] = false;
                    }
                }
                $found = true;
                break;
            }
        }

        if ($found) {
            LPPMDataManager::saveAnnouncements($announcements);
            return redirect()->back()->with('success', 'Pengumuman berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Pengumuman tidak ditemukan.');
    }

    public function announcementDelete($id)
    {
        $this->authCheck();
        $announcements = LPPMDataManager::getAnnouncements();
        $announcements = array_filter($announcements, function ($ann) use ($id) {
            return $ann['id'] != $id;
        });
        $announcements = array_values($announcements);
        LPPMDataManager::saveAnnouncements($announcements);

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function lecturers(Request $request)
    {
        $this->authCheck();
        $lecturers = LPPMDataManager::getLecturers();
        
        $search = $request->query('search');
        if (!empty($search)) {
            $keyword = strtolower(trim($search));
            if ($lecturers instanceof \Illuminate\Support\Collection) {
                $lecturers = $lecturers->filter(function ($lec) use ($keyword) {
                    $name = strtolower($lec['name'] ?? ($lec->name ?? ''));
                    $nidn = strtolower($lec['nidn'] ?? ($lec->nidn ?? ''));
                    $prodi = strtolower($lec['study_program'] ?? ($lec->study_program ?? ($lec['prodi'] ?? '')));
                    return str_contains($name, $keyword) || str_contains($nidn, $keyword) || str_contains($prodi, $keyword);
                })->values();
            } else {
                $lecturers = array_values(array_filter((array) $lecturers, function ($lec) use ($keyword) {
                    $name = strtolower($lec['name'] ?? '');
                    $nidn = strtolower($lec['nidn'] ?? '');
                    $prodi = strtolower($lec['study_program'] ?? ($lec['prodi'] ?? ''));
                    return str_contains($name, $keyword) || str_contains($nidn, $keyword) || str_contains($prodi, $keyword);
                }));
            }
        }

        $settings = LPPMDataManager::getSettings();
        return view('admin.lecturers', compact('lecturers', 'settings', 'search'));
    }

    public function lecturerCreateForm()
    {
        $this->authCheck();
        $settings = LPPMDataManager::getSettings();
        return view('admin.lecturers.create', compact('settings'));
    }

    public static function normalizeSintaUrl(?string $raw): ?string
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

    public static function normalizeScholarUrl(?string $raw): ?string
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

    public function lecturerCreate(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'nidn' => 'required',
            'email' => 'required|email',
            'study_program' => 'nullable|string',
            'scholar_url' => 'nullable|string',
            'sinta_url' => 'nullable|string',
            'avatar' => 'nullable',
        ]);

        if (!$request->filled('name') && $request->filled('nama_lengkap')) {
            $request->merge(['name' => $request->nama_lengkap]);
        }
        if (!$request->filled('expertise') && $request->filled('kepakaran')) {
            $request->merge(['expertise' => $request->kepakaran]);
        }
        if (!$request->filled('faculty') && $request->filled('fakultas')) {
            $request->merge(['faculty' => $request->fakultas]);
        }
        if (!$request->filled('study_program') && $request->filled('prodi')) {
            $request->merge(['study_program' => $request->prodi]);
        }

        $name = $request->name ?? 'Dosen Mahardika';
        $nidn = $request->nidn;
        $email = $request->email;
        $faculty = $request->faculty ?? '-';
        $rawProdi = $request->study_program ?? 'Informatika';
        if ($rawProdi === 'Kesehatan Masyarakat') {
            $rawProdi = 'Kesmas';
        }
        $validProdis = ['Kebidanan', 'Keperawatan', 'Rekam Medis', 'Kesmas', 'Informatika'];
        $studyProgram = in_array($rawProdi, $validProdis) ? $rawProdi : ($rawProdi ?: 'Informatika');
        $expertise = $request->expertise ?? 'Bidang Riset';

        $scholarUrl = self::normalizeScholarUrl($request->scholar_url) ?? ($request->scholar_url ?? '#');
        $sintaUrl = self::normalizeSintaUrl($request->sinta_url) ?? ($request->sinta_url ?? '#');

        // 1. Buat Akun Login terlebih dahulu di tabel users. Gunakan NIDN sebagai password default
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($nidn),
                'role' => 'dosen',
            ]);
        }

        // 2. Handle upload file avatar: simpan file ke folder public/storage/avatars
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $request->merge(['avatar' => $avatarPath]);
        } elseif ($request->filled('avatar_url')) {
            $avatarPath = $request->avatar_url;
            $request->merge(['avatar' => $avatarPath]);
        }

        // 3. Auto-generate slug unik
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $count = 0;
        $lecturers = LPPMDataManager::getLecturers();
        if ($lecturers instanceof \Illuminate\Support\Collection) {
            $lecturers = $lecturers->toArray();
        }
        foreach ($lecturers as $lec) {
            if (($lec['slug'] ?? '') === $slug) $count++;
        }
        if ($count > 0 || Dosen::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . time();
        }

        // 4. Simpan ke database dosens / Lecturer
        $lecturer = Lecturer::create([
            'user_id' => $user->id,
            'nama' => $name,
            'nidn' => $nidn,
            'email' => $email,
            'fakultas' => $faculty,
            'prodi' => $studyProgram,
            'kepakaran' => $expertise,
            'slug' => $slug,
            'avatar' => $avatarPath ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=256',
            'scholar_url' => $scholarUrl,
            'sinta_url' => $sintaUrl,
            'status' => 'published',
            'is_active' => true,
        ]);

        if (empty($user->dosen_id)) {
            $user->update(['dosen_id' => $lecturer->id]);
        }

        // 5. Simpan ke LPPMDataManager agar sinkron dengan direktori session
        $researchTopics = $request->research_topics ? (is_array($request->research_topics) ? $request->research_topics : array_map('trim', explode(',',$request->research_topics))) : [];

        $newLec = [
            'name' => $request->name, 
            'slug' => $slug ?? Str::slug($request->name), 
            'nidn' => $request->nidn,
            'expertise' => $request->expertise, 
            'faculty' => $request->faculty,
            'study_program' => $request->study_program, 
            'publications' => $request->publications ?? '0',
            'citations' => $request->citations ?? '0', 
            'avatar' => $request->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=256',
            'email' => $request->email, 
            'scholar_url' => $request->scholar_url ?? '#',
            'sinta_url' => $request->sinta_url ?? '#', 
            'scopus_url' => $request->scopus_url ?? '#',
            'research_topics' => $researchTopics,
            'research_history' => [['year' => date('Y'), 'title' => 'Riset Evaluasi tentang ' . $request->expertise, 'role' => 'Ketua Peneliti']],
            'service_history' => [['year' => date('Y'), 'title' => 'Pemberdayaan Masyarakat berbasis ' . $request->expertise, 'role' => 'Ketua Pengmas']],
            'selected_publications' => [['year' => date('Y'), 'title' => 'Jurnal Kajian Teoretis ' . $request->expertise, 'journal' => 'Jurnal Mahardika', 'citation' => '0']]
        ];

        $lecturers[] = $newLec;
        LPPMDataManager::saveLecturers($lecturers);

        // 6. Auto Refresh Cache Publik
        Cache::forget('list_dosen_publik');

        return redirect()->route('admin.lecturers')->with('success', 'Data profil dosen baru berhasil ditambahkan dan langsung dipublikasikan!');
    }

    public function lecturerUpdate(Request $request, $slug)
    {
        $this->authCheck();
        $request->validate([
            'nidn' => 'required',
            'email' => 'required|email',
            'study_program' => 'nullable|string',
            'scholar_url' => 'nullable|string',
            'sinta_url' => 'nullable|string',
            'avatar' => 'nullable',
        ]);

        $lecturers = LPPMDataManager::getLecturers();
        if ($lecturers instanceof \Illuminate\Support\Collection) {
            $lecturers = $lecturers->toArray();
        }
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $rawProdi = $request->prodi ?? $request->study_program;
        if ($rawProdi === 'Kesehatan Masyarakat') {
            $rawProdi = 'Kesmas';
        }
        $validProdis = ['Kebidanan', 'Keperawatan', 'Rekam Medis', 'Kesmas', 'Informatika'];
        $studyProgram = ($rawProdi && in_array($rawProdi, $validProdis)) ? $rawProdi : $rawProdi;

        $hasScholar = $request->has('scholar_url');
        $scholarVal = $hasScholar ? self::normalizeScholarUrl($request->scholar_url) : null;
        $hasSinta = $request->has('sinta_url');
        $sintaVal = $hasSinta ? self::normalizeSintaUrl($request->sinta_url) : null;

        $found = false;
        foreach ($lecturers as &$lec) {
            if ($lec['slug'] === $slug) {
                $lec['name'] = $request->nama_lengkap ?? $request->name ?? $lec['name'];
                $lec['nidn'] = $request->nidn;
                $lec['expertise'] = $request->kepakaran ?? $request->expertise ?? $lec['expertise'];
                $lec['faculty'] = $request->fakultas ?? $request->faculty ?? $lec['faculty'];
                if ($studyProgram) {
                    $lec['study_program'] = $studyProgram;
                }
                $lec['publications'] = $request->publications ?? $lec['publications'];
                $lec['citations'] = $request->citations ?? $lec['citations'];
                if ($avatarPath) {
                    $lec['avatar'] = $avatarPath;
                }
                $lec['email'] = $request->email;
                if ($hasScholar) {
                    $lec['scholar_url'] = $scholarVal;
                }
                if ($hasSinta) {
                    $lec['sinta_url'] = $sintaVal;
                }
                
                if ($request->research_topics) {
                    $lec['research_topics'] = array_filter(array_map('trim', explode(',', $request->research_topics)));
                }
                
                $found = true;
                break;
            }
        }

        if ($found) {
            LPPMDataManager::saveLecturers($lecturers);
        }

        $dbDosen = Lecturer::where('slug', $slug)->first() ?? Dosen::where('slug', $slug)->first();
        if ($dbDosen) {
            $updateData = [
                'nama' => $request->nama_lengkap ?? $request->name ?? $dbDosen->nama,
                'nidn' => $request->nidn,
                'email' => $request->email,
                'fakultas' => $request->fakultas ?? $request->faculty ?? $dbDosen->fakultas,
                'prodi' => $studyProgram ?? $dbDosen->prodi,
                'kepakaran' => $request->kepakaran ?? $request->expertise ?? $dbDosen->kepakaran,
                'status' => 'published',
                'is_active' => true,
            ];
            if ($hasScholar) {
                $updateData['scholar_url'] = $scholarVal;
            }
            if ($hasSinta) {
                $updateData['sinta_url'] = $sintaVal;
            }
            if ($avatarPath) {
                $updateData['avatar'] = $avatarPath;
            }
            $dbDosen->update($updateData);
        }

        // Auto Refresh Cache Publik
        Cache::forget('list_dosen_publik');

        return redirect()->route('admin.lecturers')->with('success', 'Profil dosen berhasil diperbarui dan dipublikasikan!');
    }

    public function lecturerDelete($slug)
    {
        $this->authCheck();
        
        $lecturers = LPPMDataManager::getLecturers();
        if ($lecturers instanceof \Illuminate\Support\Collection) {
            $lecturers = $lecturers->reject(function ($lec) use ($slug) {
                return ($lec['slug'] ?? ($lec->slug ?? null)) === $slug;
            })->values()->toArray();
        } else {
            $lecturers = array_values(array_filter((array) $lecturers, function ($lec) use ($slug) {
                return ($lec['slug'] ?? null) !== $slug;
            }));
        }
        LPPMDataManager::saveLecturers($lecturers);

        Lecturer::where('slug', $slug)->delete();
        Dosen::where('slug', $slug)->delete();
        Cache::forget('list_dosen_publik');

        return redirect()->route('admin.lecturers')->with('success', 'Profil dosen berhasil dihapus.');
    }

    public function documents()
    {
        $this->authCheck();
        $documents = LPPMDataManager::getDocuments();
        $settings = LPPMDataManager::getSettings();
        return view('admin.documents', compact('documents', 'settings'));
    }

    public function documentCreate(Request $request)
    {
        $this->authCheck();
        $request->validate(['title' => 'required', 'category' => 'required', 'type' => 'required']);

        $documents = LPPMDataManager::getDocuments();
        $nextId = 1;
        foreach ($documents as $doc) {
            if ($doc['id'] >= $nextId) $nextId = $doc['id'] + 1;
        }

        $newDoc = [
            'id' => $nextId, 'title' => $request->title, 'category' => $request->category,
            'type' => $request->type, 'size' => $request->size ?? '1.2 MB',
            'link' => $request->link ?? '#', 'status' => $request->status ?? 'Published'
        ];

        $documents[] = $newDoc;
        LPPMDataManager::saveDocuments($documents);

        return redirect()->back()->with('success', 'Dokumen baru berhasil diunggah!');
    }

    public function documentUpdate(Request $request, $id)
    {
        $this->authCheck();
        $request->validate(['title' => 'required', 'category' => 'required', 'type' => 'required']);

        $documents = LPPMDataManager::getDocuments();
        $found = false;

        foreach ($documents as &$doc) {
            if ($doc['id'] == $id) {
                $doc['title'] = $request->title; $doc['category'] = $request->category;
                $doc['type'] = $request->type; $doc['size'] = $request->size ?? $doc['size'];
                $doc['link'] = $request->link ?? $doc['link']; $doc['status'] = $request->status ?? $doc['status'];
                $found = true;
                break;
            }
        }

        if ($found) {
            LPPMDataManager::saveDocuments($documents);
            return redirect()->back()->with('success', 'Dokumen berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
    }

    public function documentDelete($id)
    {
        $this->authCheck();
        $documents = LPPMDataManager::getDocuments();
        $documents = array_filter($documents, function ($doc) use ($id) {
            return $doc['id'] != $id;
        });
        $documents = array_values($documents);
        LPPMDataManager::saveDocuments($documents);

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function journals()
    {
        $this->authCheck();
        $portals = LPPMDataManager::getPortals();
        $settings = LPPMDataManager::getSettings();
        return view('admin.journals', compact('portals', 'settings'));
    }

    public function journalUpdate(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'key' => 'required', 'title' => 'required', 'domain' => 'required',
            'link' => 'required', 'description' => 'required',
        ]);

        $portals = LPPMDataManager::getPortals();
        $key = $request->key;

        if (isset($portals[$key])) {
            $portals[$key]['title'] = $request->title;
            $portals[$key]['domain'] = $request->domain;
            $portals[$key]['link'] = $request->link;
            $portals[$key]['description'] = $request->description;
            
            LPPMDataManager::savePortals($portals);
            return redirect()->back()->with('success', 'Portal berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Portal tidak ditemukan.');
    }

    public function settings()
    {
        $this->authCheck();
        $settings = LPPMDataManager::getSettings();
        return view('admin.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'hero_headline' => 'required', 'hero_subheadline' => 'required',
            'lppm_email' => 'required|email', 'lppm_phone' => 'required', 'lppm_address' => 'required',
        ]);

        $current = LPPMDataManager::getSettings();

        $settings = array_merge($current, [
            'hero_headline' => $request->hero_headline, 'hero_subheadline' => $request->hero_subheadline,
            'lppm_email' => $request->lppm_email, 'lppm_phone' => $request->lppm_phone,
            'lppm_address' => $request->lppm_address,
            'social_facebook' => $request->social_facebook ?? '#', 'social_instagram' => $request->social_instagram ?? '#',
            'social_twitter' => $request->social_twitter ?? '#', 'social_youtube' => $request->social_youtube ?? '#',
            'link_portal_penelitian' => $request->link_portal_penelitian ?? 'https://penelitian.mahardika.ac.id',
            'link_portal_pengmas' => $request->link_portal_pengmas ?? 'https://pengmas.mahardika.ac.id',
            'haki_coordinator_name' => $request->haki_coordinator_name ?? ($current['haki_coordinator_name'] ?? 'Ibu Iffah'),
            'haki_coordinator_contact' => $request->haki_coordinator_contact ?? ($current['haki_coordinator_contact'] ?? ''),
        ]);

        LPPMDataManager::saveSettings($settings);
        return redirect()->back()->with('success', 'Konfigurasi landing page dan sentra HKI berhasil disimpan!');
    }

    /**
     * Memperbarui profil Koordinator Sentra HKI langsung dari Admin Dashboard / Pengaturan.
     */
    public function updateHakiCoordinator(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'haki_coordinator_name' => 'required|string|max:255',
            'haki_coordinator_contact' => 'nullable|string|max:255',
        ], [
            'haki_coordinator_name.required' => 'Nama Koordinator Sentra HKI wajib diisi.',
        ]);

        $name = trim($request->haki_coordinator_name);
        $contact = trim($request->haki_coordinator_contact ?? '');

        // Simpan langsung ke database settings
        \App\Models\Setting::set('haki_coordinator_name', $name);
        \App\Models\Setting::set('haki_coordinator_contact', $contact);

        // Sinkronkan ke LPPMDataManager session
        $currentSettings = LPPMDataManager::getSettings();
        $currentSettings['haki_coordinator_name'] = $name;
        $currentSettings['haki_coordinator_contact'] = $contact;
        LPPMDataManager::saveSettings($currentSettings);

        return redirect()->back()->with('success', 'Data Koordinator Sentra HKI ("' . $name . '") berhasil diperbarui dan langsung aktif di formulir pengajuan HKI!');
    }

    public function resetData()
    {
        $this->authCheck();
        LPPMDataManager::resetToDefault();
        return redirect()->back()->with('success', 'Data berhasil di-reset.');
    }

    /**
     * Menampilkan daftar berkas & LPJ dosen untuk verifikasi Admin.
     */
    public function berkasIndex()
    {
        $this->authCheck();

        $berkasList = BerkasPengajuan::with(['user', 'dosen', 'penelitian', 'pengabdian'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBerkas = $berkasList->count();
        $approvedBerkas = $berkasList->whereIn('status', ['Approved', 'Disetujui', 'approved'])->count();
        $pendingBerkas = $berkasList->whereIn('status', ['Submitted', 'Pending', 'pending'])->count();
        $revisionBerkas = $berkasList->whereIn('status', ['Revisi', 'Rejected', 'Ditolak', 'rejected'])->count();

        return view('admin.berkas.index', compact(
            'berkasList',
            'totalBerkas',
            'approvedBerkas',
            'pendingBerkas',
            'revisionBerkas'
        ));
    }

    /**
     * Mengubah status verifikasi berkas LPJ.
     */
    public function updateBerkasStatus(Request $request, $id)
    {
        $this->authCheck();

        $request->validate([
            'status' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $berkas = BerkasPengajuan::findOrFail($id);

        $updateData = ['status' => $request->status];
        if ($request->has('catatan')) {
            $updateData['catatan'] = $request->catatan;
        }

        $berkas->update($updateData);

        return redirect()->back()->with('success', 'Status berkas berhasil diubah menjadi ' . $request->status . '.');
    }

    /**
     * Manajemen Pengguna & Staf LPPM (Metode Delegasi ke AdminUserController).
     */
    public function users(Request $request)
    {
        return app(\App\Http\Controllers\AdminUserController::class)->index($request);
    }

    public function userCreate()
    {
        return app(\App\Http\Controllers\AdminUserController::class)->create();
    }

    public function userStore(Request $request)
    {
        return app(\App\Http\Controllers\AdminUserController::class)->store($request);
    }

    public function userUpdate(Request $request, $id)
    {
        return app(\App\Http\Controllers\AdminUserController::class)->update($request, $id);
    }

    public function userDestroy($id)
    {
        return app(\App\Http\Controllers\AdminUserController::class)->destroy($id);
    }
}