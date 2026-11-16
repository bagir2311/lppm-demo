<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\User;
use App\Helpers\LPPMDataManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LecturerController extends Controller
{
    /**
     * Simpan data dosen baru dan buat akun user login otomatis.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nidn' => 'required',
            'email' => 'required|email',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $namaLengkap = $request->nama_lengkap ?? $request->name;
        $nidn = $request->nidn;
        $email = $request->email;
        $fakultas = $request->fakultas ?? $request->faculty;
        $prodi = $request->prodi ?? $request->study_program;
        $kepakaran = $request->kepakaran ?? $request->expertise;

        // 1. Buat Akun Login terlebih dahulu di tabel users. Gunakan NIDN sebagai password default:
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $namaLengkap,
                'email' => $email,
                'password' => Hash::make($nidn),
                'role' => 'dosen',
            ]);
        }

        // 2. Handle upload file avatar: simpan file ke folder public/storage/avatars
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->filled('avatar_url')) {
            $avatarPath = $request->avatar_url;
        }

        // 3. Auto-generate slug unik
        $baseSlug = Str::slug($namaLengkap);
        $slug = $baseSlug;
        $count = Lecturer::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        // 4. Simpan data profil ke tabel lecturers (tabel dosens) dengan mengaitkan user_id
        $lecturer = Lecturer::create([
            'user_id' => $user->id,
            'nama' => $namaLengkap,
            'nidn' => $nidn,
            'email' => $email,
            'fakultas' => $fakultas,
            'prodi' => $prodi,
            'kepakaran' => $kepakaran,
            'slug' => $slug,
            'avatar' => $avatarPath,
            'status' => 'published',
            'is_active' => true,
        ]);

        if (empty($user->dosen_id)) {
            $user->update(['dosen_id' => $lecturer->id]);
        }

        // 5. Sinkronisasi data ke LPPMDataManager
        try {
            $lecturers = LPPMDataManager::getLecturers();
            $researchTopics = array_filter(array_map('trim', explode(',', $request->research_topics ?? '')));
            if (empty($researchTopics)) $researchTopics = ['Riset Bidang ' . ($kepakaran ?? 'Akademik')];

            $newLec = [
                'name' => $namaLengkap,
                'slug' => $slug,
                'nidn' => $nidn,
                'expertise' => $kepakaran,
                'faculty' => $fakultas,
                'study_program' => $prodi,
                'publications' => $request->publications ?? '0',
                'citations' => $request->citations ?? '0',
                'avatar' => $avatarPath ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=256',
                'email' => $email,
                'scholar_url' => \App\Http\Controllers\AdminController::normalizeScholarUrl($request->scholar_url),
                'sinta_url' => \App\Http\Controllers\AdminController::normalizeSintaUrl($request->sinta_url),
                'scopus_url' => null,
                'research_topics' => $researchTopics,
                'research_history' => [['year' => date('Y'), 'title' => 'Riset Evaluasi tentang ' . $kepakaran, 'role' => 'Ketua Peneliti']],
                'service_history' => [['year' => date('Y'), 'title' => 'Pemberdayaan Masyarakat berbasis ' . $kepakaran, 'role' => 'Ketua Pengmas']],
                'selected_publications' => [['year' => date('Y'), 'title' => 'Jurnal Kajian Teoretis ' . $kepakaran, 'journal' => 'Jurnal Mahardika', 'citation' => '0']],
            ];
            $lecturers[] = $newLec;
            LPPMDataManager::saveLecturers($lecturers);
        } catch (\Throwable $e) {
            // ignore
        }

        // 6. Auto Refresh Cache
        Cache::forget('list_dosen_publik');

        // 7. Return Redirect ke route index admin dengan pesan flash success
        return redirect()->route('admin.lecturers')->with('success', 'Data profil dosen baru berhasil ditambahkan dan langsung dipublikasikan!');
    }

    /**
     * Perbarui data dosen dan refresh cache.
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'nidn' => 'required',
            'email' => 'required|email',
            'avatar' => 'nullable',
        ]);

        $lecturer = Lecturer::where('slug', $slug)->first();
        if ($lecturer) {
            $namaLengkap = $request->nama_lengkap ?? $request->name ?? $lecturer->nama;
            $avatarPath = $lecturer->avatar;

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            } elseif ($request->filled('avatar_url')) {
                $avatarPath = $request->avatar_url;
            }

            $lecturer->update([
                'nama' => $namaLengkap,
                'nidn' => $request->nidn,
                'email' => $request->email,
                'fakultas' => $request->fakultas ?? $request->faculty ?? $lecturer->fakultas,
                'prodi' => $request->prodi ?? $request->study_program ?? $lecturer->prodi,
                'kepakaran' => $request->kepakaran ?? $request->expertise ?? $lecturer->kepakaran,
                'avatar' => $avatarPath,
                'status' => 'published',
                'is_active' => true,
            ]);
        }

        // Auto Refresh Cache
        Cache::forget('list_dosen_publik');

        return redirect()->route('admin.lecturers')->with('success', 'Profil dosen berhasil diperbarui dan dipublikasikan!');
    }
}
