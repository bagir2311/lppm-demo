@extends('layouts.app')

@section('title', 'Unggah Laporan Pertanggungjawaban (LPJ) - LPPM Mahardika')

@section('content')
<div class="pt-28 pb-16 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Tombol Kembali & Breadcrumb -->
        <div class="flex items-center justify-between">
            <a href="{{ route('dosen.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:text-[#00295f] dark:hover:text-blue-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Dasbor Dosen</span>
            </a>
            <span class="text-[11px] font-mono text-gray-400 dark:text-gray-500">Sistem LPJ Terintegrasi Mahardika</span>
        </div>

        <!-- Header Card Info Pelaporan LPJ -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 shadow-sm transition-colors duration-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 dark:border-gray-700 pb-5">
                <div class="space-y-1">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider">
                        <span>📊 Form Laporan Kemajuan &amp; LPJ Akhir</span>
                    </div>
                    <h1 class="font-primary font-bold text-2xl text-gray-900 dark:text-white">Pelaporan Pertanggungjawaban (LPJ)</h1>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Pengumpulan berkas kemajuan 70% dan laporan akhir 100% beserta bukti luaran riil kegiatan.</p>
                </div>
                @if(isset($proposal))
                <div class="p-3 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 rounded-xl text-xs font-semibold text-blue-900 dark:text-blue-200 shrink-0 sm:text-right">
                    <span>Skema: {{ $proposal->skema }}</span>
                    <div class="font-bold text-sm text-gray-900 dark:text-white mt-0.5 truncate max-w-xs">{{ $proposal->judul }}</div>
                </div>
                @endif
            </div>

            <div class="mt-4 p-3.5 rounded-xl bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-gray-600 text-xs text-gray-700 dark:text-gray-300">
                <span class="font-bold text-gray-900 dark:text-white">Panduan Pengisian:</span>
                <ul class="list-disc list-inside mt-1 space-y-0.5 text-[11px] text-gray-600 dark:text-gray-300">
                    <li><strong>Fase 70% (Oktober):</strong> Meliputi Laporan Kemajuan Substansi, Rekapitulasi Realisasi Keuangan 70%, serta Dokumentasi Foto/Video kegiatan. Luaran tidak wajib.</li>
                    <li><strong>Fase 100% (Desember):</strong> Meliputi Laporan Akhir Lengkap, LPJ Keuangan Final 100%, Dokumentasi, serta <strong>Wajib melampirkan Luaran</strong> (Artikel, Berita Koran, HKI, atau Video YouTube).</li>
                </ul>
            </div>
        </div>

        @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 dark:bg-red-950/40 dark:border-red-800 dark:text-red-200 text-xs space-y-1">
            <p class="font-bold">Mohon perbaiki data berikut:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Formulir Unggah LPJ Dinamis -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 shadow-sm transition-colors duration-200"
             x-data="{
                fase: '{{ old('fase_pelaporan', '70%') }}',
                luaran: '{{ old('pilihan_luaran', '') }}'
             }">
            <form action="{{ route('dosen.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Pilih Proposal / Kegiatan Terkait -->
                <div>
                    <label for="proposal_id" class="block text-xs font-bold text-gray-900 dark:text-white mb-1">
                        Pilih Usulan Kegiatan yang Dilaporkan <span class="text-red-500">*</span>
                    </label>
                    <select id="proposal_id" name="proposal_id" required class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                        @foreach($proposalsDidanai as $p)
                            <option value="{{ $p->id }}" {{ (isset($proposal) && $proposal->id == $p->id) || old('proposal_id') == $p->id ? 'selected' : '' }}>
                                [{{ $p->skema }} - {{ $p->status }}] {{ $p->judul }} ({{ $p->dosen->nama ?? 'Dosen' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Fase Pelaporan (70% vs 100%) -->
                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-2">
                        Fase Pelaporan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="relative flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700/50 border-gray-300 dark:border-gray-600 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 dark:has-[:checked]:bg-emerald-950/40 has-[:checked]:ring-2 has-[:checked]:ring-emerald-600">
                            <input type="radio" name="fase_pelaporan" value="70%" x-model="fase" {{ old('fase_pelaporan', '70%') == '70%' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-600">
                            <div class="ml-3">
                                <span class="block text-xs font-bold text-gray-900 dark:text-white">📊 Fase 70% (Laporan Kemajuan / Oktober)</span>
                                <span class="block text-[11px] text-gray-500 dark:text-gray-400">Pencairan tahap I, rekap kemajuan substansi &amp; keuangan 70%.</span>
                            </div>
                        </label>
                        <label class="relative flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700/50 border-gray-300 dark:border-gray-600 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 dark:has-[:checked]:bg-emerald-950/40 has-[:checked]:ring-2 has-[:checked]:ring-emerald-600">
                            <input type="radio" name="fase_pelaporan" value="100%" x-model="fase" {{ old('fase_pelaporan') == '100%' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-600">
                            <div class="ml-3">
                                <span class="block text-xs font-bold text-gray-900 dark:text-white">🏁 Fase 100% (Laporan Akhir / Desember)</span>
                                <span class="block text-[11px] text-gray-500 dark:text-gray-400">Pertanggungjawaban tuntas, LPJ 100%, dan bukti luaran wajib.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3 Berkas Wajib: Substansi, Keuangan, Dokumentasi -->
                <div class="space-y-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>📁 Berkas Wajib Laporan</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- File Laporan Substansi -->
                        <div class="p-4 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-slate-700/40 space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 flex items-center justify-center font-bold text-xs">📄</div>
                                <label class="block text-xs font-bold text-gray-900 dark:text-white">
                                    Laporan Substansi <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">Naskah laporan kemajuan / akhir (PDF/DOC, Max 15MB).</span>
                            <input type="file" name="file_laporan_substansi" required accept=".pdf,.doc,.docx" class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer cursor-pointer">
                        </div>

                        <!-- File Laporan Keuangan -->
                        <div class="p-4 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-slate-700/40 space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-bold text-xs">💰</div>
                                <label class="block text-xs font-bold text-gray-900 dark:text-white">
                                    Laporan Keuangan &amp; Nota <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">Rekap RAB realisasi &amp; scan nota bukti transaksi (PDF/XLS, Max 15MB).</span>
                            <input type="file" name="file_laporan_keuangan" required accept=".pdf,.xls,.xlsx" class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 file:cursor-pointer cursor-pointer">
                        </div>

                        <!-- File Dokumentasi (Foto/Video) -->
                        <div class="p-4 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-slate-700/40 space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 flex items-center justify-center font-bold text-xs">📸</div>
                                <label class="block text-xs font-bold text-gray-900 dark:text-white">
                                    Dokumentasi Kegiatan <span class="text-red-500">*</span>
                                </label>
                            </div>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">Foto survei, kegiatan mitra, lab, instrumen (PDF, ZIP, JPG, Max 20MB).</span>
                            <input type="file" name="file_dokumentasi" required accept=".pdf,.zip,.rar,.jpg,.jpeg,.png" class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:cursor-pointer cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- Bagian Luaran (Hanya Tampil Jika Fase 100%) -->
                <div x-show="fase === '100%'" x-transition class="p-5 rounded-2xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 space-y-4">
                    <div class="flex items-center justify-between border-b border-amber-200/80 dark:border-amber-800 pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200 flex items-center gap-2">
                                <span>🎯 Luaran Wajib (Fase Akhir 100%)</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-amber-200 text-amber-900 dark:bg-amber-900 dark:text-amber-200">WAJIB</span>
                            </h3>
                            <p class="text-[11px] text-gray-600 dark:text-gray-400 mt-0.5">Sesuai kontrak hibah, pilih salah satu capaian luaran riil kegiatan riset atau pengabdian Anda.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pilihan_luaran" class="block text-xs font-bold text-gray-900 dark:text-white mb-1">
                                Pilihan Jenis Luaran <span class="text-red-500">*</span>
                            </label>
                            <select id="pilihan_luaran" name="pilihan_luaran" x-model="luaran" :required="fase === '100%'" class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                                <option value="">-- Pilih Jenis Luaran Wajib --</option>
                                <option value="Artikel">📝 Artikel (Jurnal Ilmiah Bereputasi / SINTA)</option>
                                <option value="Berita di Koran">📰 Berita di Koran / Media Massa Cetak &amp; Online</option>
                                <option value="HKI">💡 Hak Kekayaan Intelektual (HKI / Paten)</option>
                                <option value="Video YouTube">🎥 Video YouTube / Dokumenter Diseminasi</option>
                            </select>
                        </div>

                        <div>
                            <label for="url_luaran" class="block text-xs font-bold text-gray-900 dark:text-white mb-1">
                                Tautan / URL Luaran (Link Publikasi / YouTube / Berita)
                            </label>
                            <input type="url" id="url_luaran" name="url_luaran" value="{{ old('url_luaran') }}" placeholder="https://..." class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white placeholder-gray-400 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">
                            Bukti File Luaran (Scan Bukti Terbit / Sertifikat HKI / PDF Artikel / Tangkapan Layar Berita)
                        </label>
                        <input type="file" name="file_luaran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer cursor-pointer">
                    </div>
                </div>

                <!-- Notifikasi Info Fase 70% -->
                <div x-show="fase === '70%'" class="p-3.5 rounded-xl bg-blue-50 dark:bg-slate-700/40 border border-blue-200 dark:border-blue-900/40 text-[11px] text-blue-800 dark:text-blue-300">
                    ℹ️ Pada <strong>Fase 70%</strong>, input data Luaran ditiadakan. Fokus penilaian adalah progres substansi kegiatan dan realisasi penggunaan dana 70%.
                </div>

                <!-- Tombol Submit & Aksi -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">
                        Pastikan nota keuangan dan laporan substansi telah lengkap sebelum menekan kirim.
                    </p>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('dosen.dashboard') }}" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition inline-flex items-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Unggah Laporan LPJ</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
