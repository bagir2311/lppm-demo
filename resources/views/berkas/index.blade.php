@extends('layouts.app')

@section('title', 'Unggah Berkas & LPJ Keuangan LPPM Mahardika')

@section('content')
<div class="pt-28 pb-16 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ uploadModal: false, statusModal: false, selectedBerkas: {} }">
        
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="space-y-1">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-green-50 dark:bg-green-900/40 text-green-700 dark:text-green-300 text-xs font-bold uppercase tracking-wider">
                    <span>📂 Pengganti Google Form</span>
                </div>
                <h1 class="font-primary font-bold text-2xl text-gray-900 dark:text-white">Pengumpulan Berkas &amp; LPJ Keuangan Terintegrasi</h1>
                <p class="text-xs text-gray-700 dark:text-gray-300">Portal unggah dokumen proposal, laporan kemajuan, laporan akhir, serta Laporan Pertanggungjawaban (LPJ) Keuangan.</p>
            </div>

            <button type="button" @click="uploadModal = true" class="px-5 py-3 bg-green-700 hover:bg-green-800 text-white rounded-xl text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm flex items-center space-x-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>+ Unggah Berkas / LPJ Baru</span>
            </button>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/40 dark:border-green-800 dark:text-green-200 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button type="button" @click="$el.parentElement.remove()" class="text-green-700 hover:text-green-900 dark:text-green-300 cursor-pointer">✕</button>
        </div>
        @endif

        <!-- Berkas List Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Daftar Dokumen Berkas &amp; LPJ Terdaftar</h3>
                <span class="text-xs text-gray-700 dark:text-gray-300 font-semibold">Total: {{ $berkasList->count() }} Dokumen</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold uppercase text-xs border-b border-gray-200 dark:border-gray-700">
                            <th class="py-4 px-6">Jenis Dokumen</th>
                            <th class="py-4 px-6">Nama Berkas File</th>
                            <th class="py-4 px-6">Dosen Pengunggah</th>
                            <th class="py-4 px-6">Judul Riset / PKM</th>
                            <th class="py-4 px-6">Status Review</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        @forelse($berkasList as $item)
                        <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-50/70 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold {{ $item->jenis_berkas === 'LPJ Keuangan' ? 'bg-red-50 text-red-700 dark:bg-red-900/40 dark:text-red-300' : 'bg-blue-50 text-[#00295f] dark:bg-blue-900/40 dark:text-blue-300' }}">
                                    {{ $item->jenis_berkas }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900 dark:text-white flex items-center space-x-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="line-clamp-1">{{ $item->nama_file }}</span>
                                </div>
                                <div class="text-[10px] text-gray-700 dark:text-gray-300 mt-0.5">Ukuran: {{ $item->ukuran_file ?? 'PDF File' }}</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                {{ $item->dosen->nama ?? 'Dosen Insan Mahardika' }}
                            </td>
                            <td class="py-4 px-6 max-w-xs">
                                <div class="line-clamp-2 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->penelitian->judul ?? $item->pengabdian->judul ?? 'Pengajuan Hibah Riset' }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($item->status === 'Disetujui')
                                    <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Disetujui</span>
                                @elseif($item->status === 'Revisi')
                                    <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Perlu Revisi</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Pending / Submitted</span>
                                @endif
                                @if($item->catatan)
                                    <div class="text-[10px] text-gray-600 dark:text-gray-300 italic mt-1 line-clamp-1">"{{ $item->catatan }}"</div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button type="button" @click="selectedBerkas = {{ json_encode($item) }}; statusModal = true" 
                                        class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer">
                                    Verifikasi LPPM
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-700 dark:text-gray-300">Belum ada berkas terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Upload Berkas -->
        <div x-show="uploadModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-xl w-full p-6 space-y-4 shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Form Unggah Berkas &amp; LPJ Keuangan</h3>
                    <button type="button" @click="uploadModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">✕</button>
                </div>

                <form action="{{ route('berkas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Jenis Berkas / Laporan *</label>
                        <select name="jenis_berkas" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Proposal" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Proposal Hibah Riset / Pengmas</option>
                            <option value="Laporan Kemajuan" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Laporan Kemajuan (Progress 70%)</option>
                            <option value="Laporan Akhir" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Laporan Akhir Penelitian / Pengmas</option>
                            <option value="Dokumentasi Kegiatan" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Dokumentasi Foto &amp; Video Kegiatan</option>
                            <option value="LPJ Keuangan" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">LPJ Keuangan &amp; Bukti Transfer (Laporan Keuangan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Dosen Pengunggah *</label>
                        <select name="dosen_id" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">{{ $d->nama }} ({{ $d->prodi }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Pilih Judul Penelitian Terkait (Opsional)</label>
                        <select name="penelitian_id" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">-- Tidak Terikat Penelitian --</option>
                            @foreach($penelitians as $p)
                                <option value="{{ $p->id }}" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">{{ $p->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Pilih Berkas Dokumen (PDF, ZIP, DOCX, Maks 10MB) *</label>
                        <input type="file" name="berkas_file" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" placeholder="Catatan khusus untuk verifikator keuangan LPPM..." class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="uploadModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white text-xs font-bold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Unggah Berkas Dokumen</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Verifikasi Berkas (Admin) -->
        <div x-show="statusModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Verifikasi Status Berkas LPPM</h3>
                    <button type="button" @click="statusModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">✕</button>
                </div>

                <form :action="'{{ url('berkas/status') }}/' + selectedBerkas.id" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Status Verifikasi *</label>
                        <select name="status" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Disetujui" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Disetujui &amp; Valid</option>
                            <option value="Revisi" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Perlu Revisi Dokumen</option>
                            <option value="Submitted" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Submitted (Dalam Antrean)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Catatan Revisi / Evaluasi LPPM</label>
                        <textarea name="catatan" rows="3" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Keterangan kelengkapan nota / berkas..."></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="statusModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-[#00295f] hover:bg-blue-900 text-white text-xs font-bold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Simpan Status</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
