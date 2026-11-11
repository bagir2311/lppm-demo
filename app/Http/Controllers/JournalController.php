<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Helpers\LPPMDataManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    private function authCheck()
    {
        if (Auth::check() && Auth::user()->role === 'dosen') {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat mengakses halaman ini.');
        }

        if (!Auth::check() && !session()->has('admin_logged_in')) {
            redirect()->route('admin.login')->send();
            exit;
        }
    }

    /**
     * Menampilkan daftar portal dan jurnal ilmiah.
     */
    public function index()
    {
        $this->authCheck();

        $journals = Journal::latest()->get();
        $portals = LPPMDataManager::getPortals();
        $settings = LPPMDataManager::getSettings();

        return view('admin.journals', compact('journals', 'portals', 'settings'));
    }

    /**
     * Menyimpan data portal/jurnal baru ke database.
     */
    public function store(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'nama_portal' => 'required_without:title|string|max:255',
            'title'       => 'nullable|string|max:255',
            'url'         => 'required_without:link|url|max:255',
            'link'        => 'nullable|url|max:255',
            'deskripsi'   => 'nullable|string',
            'description' => 'nullable|string',
            'domain'      => 'nullable|string|max:255',
        ]);

        $namaPortal = $request->nama_portal ?: $request->title;
        $url = $request->url ?: $request->link;
        $deskripsi = $request->deskripsi ?: $request->description;
        
        $domain = $request->domain;
        if (empty($domain) && $url) {
            $host = parse_url($url, PHP_URL_HOST);
            $path = parse_url($url, PHP_URL_PATH);
            $domain = $host ? ($host . ($path ? rtrim($path, '/') : '')) : $url;
        }

        Journal::create([
            'nama_portal' => $namaPortal,
            'domain'      => $domain,
            'url'         => $url,
            'deskripsi'   => $deskripsi,
        ]);

        return redirect()->back()->with('success', 'Portal/Jurnal baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui data portal/jurnal yang ada.
     */
    public function update(Request $request, $id = null)
    {
        $this->authCheck();

        $targetId = $id ?? $request->id;
        $journal = null;

        if ($targetId && is_numeric($targetId)) {
            $journal = Journal::find($targetId);
        }

        if (!$journal && $request->filled('key')) {
            $journal = Journal::where('domain', 'like', '%' . $request->key . '%')->first();
        }

        $namaPortal = $request->nama_portal ?: ($request->title ?: ($journal ? $journal->nama_portal : null));
        $url = $request->url ?: ($request->link ?: ($journal ? $journal->url : null));
        $deskripsi = $request->deskripsi ?: ($request->description ?: ($journal ? $journal->deskripsi : null));
        $domain = $request->domain ?: ($journal ? $journal->domain : null);

        if ($journal) {
            $journal->update([
                'nama_portal' => $namaPortal ?? $journal->nama_portal,
                'domain'      => $domain ?? $journal->domain,
                'url'         => $url ?? $journal->url,
                'deskripsi'   => $deskripsi ?? $journal->deskripsi,
            ]);
        } elseif ($request->filled('key')) {
            // Fallback update ke session LPPMDataManager jika key lama masih digunakan
            $portals = LPPMDataManager::getPortals();
            $key = $request->key;
            if (isset($portals[$key])) {
                $portals[$key]['title'] = $namaPortal;
                $portals[$key]['domain'] = $domain;
                $portals[$key]['link'] = $url;
                $portals[$key]['description'] = $deskripsi;
                LPPMDataManager::savePortals($portals);
            }
        }

        return redirect()->back()->with('success', 'Konfigurasi portal/jurnal berhasil diperbarui!');
    }

    /**
     * Menghapus portal/jurnal dari database.
     */
    public function destroy($id)
    {
        $this->authCheck();

        $journal = Journal::findOrFail($id);
        $journal->delete();

        return redirect()->back()->with('success', 'Portal/Jurnal berhasil dihapus!');
    }
}
