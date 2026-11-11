<?php

namespace App\Http\Controllers;

use App\Models\BerkasPengajuan;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Dosen;
use Illuminate\Http\Request;

class BerkasController extends Controller
{
    public function index()
    {
        $berkasList = BerkasPengajuan::with(['user', 'dosen', 'penelitian', 'pengabdian'])
            ->orderBy('created_at', 'desc')
            ->get();

        $penelitians = Penelitian::all();
        $pengabdians = Pengabdian::all();
        $dosens = Dosen::all();

        return view('berkas.index', compact('berkasList', 'penelitians', 'pengabdians', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_berkas' => 'required',
            'fase_pelaporan' => 'nullable|string',
            'pilihan_luaran' => 'nullable|string',
            'berkas_file' => 'required|file|mimes:pdf,doc,docx,zip,rar,jpg,png|max:10240',
        ]);

        $file = $request->file('berkas_file');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads/lppm_berkas', $namaFile, 'public');
        $ukuranKb = round($file->getSize() / 1024, 1) . ' KB';

        $dosenId = $request->dosen_id ?? Dosen::first()->id;

        BerkasPengajuan::create([
            'user_id' => session('user_id', 1),
            'dosen_id' => $dosenId,
            'penelitian_id' => $request->penelitian_id,
            'pengabdian_id' => $request->pengabdian_id,
            'jenis_berkas' => $request->jenis_berkas,
            'fase_pelaporan' => $request->fase_pelaporan,
            'pilihan_luaran' => $request->fase_pelaporan === '100%' ? $request->pilihan_luaran : null,
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => '/storage/' . $filePath,
            'ukuran_file' => $ukuranKb,
            'status' => 'Submitted',
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Berkas ' . $request->jenis_berkas . ' berhasil diunggah!');
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role === 'dosen') {
            abort(403, 'Akses Terlarang: Hanya Admin yang dapat memperbarui status berkas ini.');
        }

        $berkas = BerkasPengajuan::findOrFail($id);
        $status = $request->status ?? 'approved';

        $updateData = ['status' => $status];
        if ($request->filled('catatan')) {
            $updateData['catatan'] = $request->catatan;
        }

        $berkas->update($updateData);

        return redirect()->back()->with('success', 'Status berkas berhasil diperbarui!');
    }
}
