<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BerkasPengajuan;
use App\Models\Surat;
use App\Models\SuratPengajuan;

class DosenController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama Dosen.
     * Hanya pengguna dengan role 'dosen' yang diizinkan mengakses.
     */
    public function dashboard()
    {
        $user = auth()->user();
        $dosen = $user ? \App\Models\Dosen::where('email', $user->email)->orWhere('nama', $user->name)->first() : null;
        $dosenId = $dosen->id ?? ($user->id ?? 1);

        $proposals = \App\Models\Proposal::where('dosen_id', $dosenId)
            ->orWhere('dosen_id', $user->id ?? 1)
            ->latest()
            ->get();

        $surats = \App\Models\Surat::where('nama_dosen', $user->name ?? '')->latest()->get();
        if ($surats->isEmpty()) {
            $surats = \App\Models\Surat::latest()->get();
        }

        $berkas = \App\Models\BerkasPengajuan::latest()->get();

        return view('dosen.dashboard', compact('proposals', 'surats', 'berkas'));
    }
}
