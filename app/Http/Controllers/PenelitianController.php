<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;

class PenelitianController extends Controller
{
    public function index()
    {
        $proposals = Proposal::where('user_id', Auth::id())->get();
        return view('dosen.penelitian.index', compact('proposals'));
    }

    public function create()
    {
        return view('dosen.penelitian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:Penelitian,Pengabdian',
            'file_proposal' => 'required|mimes:pdf|max:5120',
        ]);

        $filePath = $request->file('file_proposal')->store('proposals', 'public');

        Proposal::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'jenis' => $request->jenis,
            'file_path' => $filePath,
            'status' => 'Menunggu Review',
        ]);

        return redirect('/penelitian')->with('success', 'Proposal berhasil diajukan!');
    }
}