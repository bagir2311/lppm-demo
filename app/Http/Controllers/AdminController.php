<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LPPMDataManager;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Check if administrator is logged in.
     */
    private function isAuth()
    {
        return session()->has('admin_logged_in');
    }

    /**
     * Redirect helper if unauthorized.
     */
    private function authCheck()
    {
        if (!$this->isAuth()) {
            redirect()->route('admin.login')->send();
            exit;
        }
    }

    /**
     * Show login form.
     */
    public function loginForm()
    {
        if ($this->isAuth()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle login submit.
     */
    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($request->email === 'superadmin@mahardika.ac.id' && $request->password === 'password') {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang, Superadmin LPPM Mahardika!');
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Kredensial login superadmin salah. Silakan coba lagi.');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request = null)
    {
        session()->forget('admin_logged_in');
        if ($request) {
            $request->session()->forget('admin_logged_in');
        }
        return redirect()->route('landing.index')->with('success', 'Logout admin berhasil.');
    }

    /**
     * Show admin dashboard.
     */
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
            'totalAnnouncements',
            'totalLecturers',
            'totalDocs',
            'activePopups',
            'settings'
        ));
    }

    /**
     * List all Announcements.
     */
    public function announcements()
    {
        $this->authCheck();
        $announcements = LPPMDataManager::getAnnouncements();
        $settings = LPPMDataManager::getSettings();
        return view('admin.announcements', compact('announcements', 'settings'));
    }

    /**
     * Create Announcement.
     */
    public function announcementCreate(Request $request)
    {
        $this->authCheck();
        
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'category_type' => 'required',
            'summary' => 'required',
        ]);

        $announcements = LPPMDataManager::getAnnouncements();
        
        // Find next ID
        $nextId = 1;
        foreach ($announcements as $ann) {
            if ($ann['id'] >= $nextId) {
                $nextId = $ann['id'] + 1;
            }
        }

        $newAnn = [
            'id' => $nextId,
            'title' => $request->title,
            'date' => date('d M Y'),
            'category' => $request->category,
            'category_type' => $request->category_type,
            'summary' => $request->summary,
            'is_important' => $request->category_type === 'important',
            'is_new' => true,
            'file_size' => $request->file_size ?? '1.2 MB',
            'link' => $request->link ?? '#',
            'show_popup' => $request->has('show_popup'),
            'start_date' => $request->start_date ?? date('Y-m-d'),
            'end_date' => $request->end_date ?? date('Y-m-d', strtotime('+30 days'))
        ];

        // If this is set as popup, un-popup other announcements
        if ($newAnn['show_popup']) {
            foreach ($announcements as &$ann) {
                $ann['show_popup'] = false;
            }
        }

        $announcements[] = $newAnn;
        LPPMDataManager::saveAnnouncements($announcements);

        return redirect()->back()->with('success', 'Pengumuman baru berhasil dipublikasikan!');
    }

    /**
     * Update Announcement.
     */
    public function announcementUpdate(Request $request, $id)
    {
        $this->authCheck();

        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'category_type' => 'required',
            'summary' => 'required',
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
                
                // If this is set as popup, un-popup other announcements
                if ($ann['show_popup']) {
                    foreach ($announcements as &$other) {
                        if ($other['id'] != $id) {
                            $other['show_popup'] = false;
                        }
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

    /**
     * Delete Announcement.
     */
    public function announcementDelete($id)
    {
        $this->authCheck();
        $announcements = LPPMDataManager::getAnnouncements();
        
        $announcements = array_filter($announcements, function ($ann) use ($id) {
            return $ann['id'] != $id;
        });

        // Re-index array
        $announcements = array_values($announcements);
        LPPMDataManager::saveAnnouncements($announcements);

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * List all Lecturers.
     */
    public function lecturers()
    {
        $this->authCheck();
        $lecturers = LPPMDataManager::getLecturers();
        $settings = LPPMDataManager::getSettings();
        return view('admin.lecturers', compact('lecturers', 'settings'));
    }

    /**
     * Create Lecturer.
     */
    public function lecturerCreate(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'name' => 'required',
            'nidn' => 'required',
            'expertise' => 'required',
            'faculty' => 'required',
            'study_program' => 'required',
            'email' => 'required|email',
        ]);

        $lecturers = LPPMDataManager::getLecturers();
        $slug = Str::slug($request->name);

        // Handle slug collisions
        $count = 0;
        foreach ($lecturers as $lec) {
            if ($lec['slug'] === $slug) {
                $count++;
            }
        }
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        // Research, Services and Publications dummy arrays
        $researchTopics = array_filter(array_map('trim', explode(',', $request->research_topics ?? '')));
        if (empty($researchTopics)) {
            $researchTopics = ['Riset Bidang ' . $request->expertise];
        }

        $newLec = [
            'name' => $request->name,
            'slug' => $slug,
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
            'research_history' => [
                ['year' => date('Y'), 'title' => 'Riset Evaluasi tentang ' . $request->expertise, 'role' => 'Ketua Peneliti'],
            ],
            'service_history' => [
                ['year' => date('Y'), 'title' => 'Pemberdayaan Masyarakat berbasis kepakaran ' . $request->expertise, 'role' => 'Ketua Pengmas'],
            ],
            'selected_publications' => [
                ['year' => date('Y'), 'title' => 'Jurnal Kajian Teoretis ' . $request->expertise, 'journal' => 'Jurnal Universitas Insan Mahardika', 'citation' => '0']
            ]
        ];

        $lecturers[] = $newLec;
        LPPMDataManager::saveLecturers($lecturers);

        return redirect()->back()->with('success', 'Data profil dosen baru berhasil ditambahkan!');
    }

    /**
     * Update Lecturer.
     */
    public function lecturerUpdate(Request $request, $slug)
    {
        $this->authCheck();

        $request->validate([
            'name' => 'required',
            'nidn' => 'required',
            'expertise' => 'required',
            'faculty' => 'required',
            'study_program' => 'required',
            'email' => 'required|email',
        ]);

        $lecturers = LPPMDataManager::getLecturers();
        $found = false;

        foreach ($lecturers as &$lec) {
            if ($lec['slug'] === $slug) {
                $lec['name'] = $request->name;
                $lec['nidn'] = $request->nidn;
                $lec['expertise'] = $request->expertise;
                $lec['faculty'] = $request->faculty;
                $lec['study_program'] = $request->study_program;
                $lec['publications'] = $request->publications ?? $lec['publications'];
                $lec['citations'] = $request->citations ?? $lec['citations'];
                $lec['avatar'] = $request->avatar ?? $lec['avatar'];
                $lec['email'] = $request->email;
                $lec['scholar_url'] = $request->scholar_url ?? $lec['scholar_url'];
                $lec['sinta_url'] = $request->sinta_url ?? $lec['sinta_url'];
                $lec['scopus_url'] = $request->scopus_url ?? $lec['scopus_url'];
                
                if ($request->research_topics) {
                    $lec['research_topics'] = array_filter(array_map('trim', explode(',', $request->research_topics)));
                }
                
                $found = true;
                break;
            }
        }

        if ($found) {
            LPPMDataManager::saveLecturers($lecturers);
            return redirect()->back()->with('success', 'Profil dosen berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Profil dosen tidak ditemukan.');
    }

    /**
     * Delete Lecturer.
     */
    public function lecturerDelete($slug)
    {
        $this->authCheck();
        $lecturers = LPPMDataManager::getLecturers();

        $lecturers = array_filter($lecturers, function ($lec) use ($slug) {
            return $lec['slug'] !== $slug;
        });

        $lecturers = array_values($lecturers);
        LPPMDataManager::saveLecturers($lecturers);

        return redirect()->back()->with('success', 'Profil dosen berhasil dihapus.');
    }

    /**
     * List all Documents.
     */
    public function documents()
    {
        $this->authCheck();
        $documents = LPPMDataManager::getDocuments();
        $settings = LPPMDataManager::getSettings();
        return view('admin.documents', compact('documents', 'settings'));
    }

    /**
     * Create Document.
     */
    public function documentCreate(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'type' => 'required',
        ]);

        $documents = LPPMDataManager::getDocuments();
        
        $nextId = 1;
        foreach ($documents as $doc) {
            if ($doc['id'] >= $nextId) {
                $nextId = $doc['id'] + 1;
            }
        }

        $newDoc = [
            'id' => $nextId,
            'title' => $request->title,
            'category' => $request->category,
            'type' => $request->type,
            'size' => $request->size ?? '1.2 MB',
            'link' => $request->link ?? '#',
            'status' => $request->status ?? 'Published'
        ];

        $documents[] = $newDoc;
        LPPMDataManager::saveDocuments($documents);

        return redirect()->back()->with('success', 'Dokumen/Peraturan baru berhasil diunggah!');
    }

    /**
     * Update Document.
     */
    public function documentUpdate(Request $request, $id)
    {
        $this->authCheck();

        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'type' => 'required',
        ]);

        $documents = LPPMDataManager::getDocuments();
        $found = false;

        foreach ($documents as &$doc) {
            if ($doc['id'] == $id) {
                $doc['title'] = $request->title;
                $doc['category'] = $request->category;
                $doc['type'] = $request->type;
                $doc['size'] = $request->size ?? $doc['size'];
                $doc['link'] = $request->link ?? $doc['link'];
                $doc['status'] = $request->status ?? $doc['status'];
                $found = true;
                break;
            }
        }

        if ($found) {
            LPPMDataManager::saveDocuments($documents);
            return redirect()->back()->with('success', 'Dokumen/Peraturan berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
    }

    /**
     * Delete Document.
     */
    public function documentDelete($id)
    {
        $this->authCheck();
        $documents = LPPMDataManager::getDocuments();

        $documents = array_filter($documents, function ($doc) use ($id) {
            return $doc['id'] != $id;
        });

        $documents = array_values($documents);
        LPPMDataManager::saveDocuments($documents);

        return redirect()->back()->with('success', 'Dokumen/Peraturan berhasil dihapus.');
    }

    /**
     * List all Journals.
     */
    public function journals()
    {
        $this->authCheck();
        $portals = LPPMDataManager::getPortals();
        $settings = LPPMDataManager::getSettings();
        return view('admin.journals', compact('portals', 'settings'));
    }

    /**
     * Update Portal/Journal Links.
     */
    public function journalUpdate(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'key' => 'required',
            'title' => 'required',
            'domain' => 'required',
            'link' => 'required',
            'description' => 'required',
        ]);

        $portals = LPPMDataManager::getPortals();
        $key = $request->key;

        if (isset($portals[$key])) {
            $portals[$key]['title'] = $request->title;
            $portals[$key]['domain'] = $request->domain;
            $portals[$key]['link'] = $request->link;
            $portals[$key]['description'] = $request->description;
            
            LPPMDataManager::savePortals($portals);
            return redirect()->back()->with('success', 'Portal ' . $portals[$key]['title'] . ' berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Portal tidak ditemukan.');
    }

    /**
     * Show general Landing Page Settings.
     */
    public function settings()
    {
        $this->authCheck();
        $settings = LPPMDataManager::getSettings();
        return view('admin.settings', compact('settings'));
    }

    /**
     * Update General Settings.
     */
    public function settingsUpdate(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'hero_headline' => 'required',
            'hero_subheadline' => 'required',
            'lppm_email' => 'required|email',
            'lppm_phone' => 'required',
            'lppm_address' => 'required',
        ]);

        $settings = [
            'hero_headline' => $request->hero_headline,
            'hero_subheadline' => $request->hero_subheadline,
            'lppm_email' => $request->lppm_email,
            'lppm_phone' => $request->lppm_phone,
            'lppm_address' => $request->lppm_address,
            'social_facebook' => $request->social_facebook ?? '#',
            'social_instagram' => $request->social_instagram ?? '#',
            'social_twitter' => $request->social_twitter ?? '#',
            'social_youtube' => $request->social_youtube ?? '#',
            'link_portal_penelitian' => $request->link_portal_penelitian ?? 'https://penelitian.mahardika.ac.id',
            'link_portal_pengmas' => $request->link_portal_pengmas ?? 'https://pengmas.mahardika.ac.id',
        ];

        LPPMDataManager::saveSettings($settings);

        return redirect()->back()->with('success', 'Konfigurasi landing page LPPM berhasil disimpan!');
    }

    /**
     * Reset Session Data to Defaults.
     */
    public function resetData()
    {
        $this->authCheck();
        LPPMDataManager::resetToDefault();
        return redirect()->back()->with('success', 'Seluruh data sistem telah berhasil di-reset ke nilai default bawaan.');
    }
}
