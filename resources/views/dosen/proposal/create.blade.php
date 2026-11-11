@extends('layouts.app')

@section('title', 'Pengusulan Proposal Hibah - LPPM Mahardika')

@section('content')
<div class="pt-28 pb-16 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Tombol Kembali & Breadcrumb -->
        <div class="flex items-center justify-between">
            <a href="{{ route('dosen.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:text-[#00295f] dark:hover:text-blue-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Dasbor Utama</span>
            </a>
            <span class="text-[11px] font-mono text-gray-400 dark:text-gray-500">BIMA LPPM System v2.0</span>
        </div>

        <!-- Header Card Info Gelombang Buka -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 shadow-sm transition-colors duration-200"
             x-data="{ 
                skemaPilihan: '{{ old('skema', 'Penelitian') }}',
                jenisHibah: '{{ old('jenis_hibah', 'Internal LPPM') }}'
             }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 dark:border-gray-700 pb-5">
                <div class="space-y-1">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/40 text-[#00295f] dark:text-blue-300 text-xs font-bold uppercase tracking-wider">
                        <span>📝 Pengusulan Proposal Riset &amp; Pengabdian</span>
                    </div>
                    <h1 class="font-primary font-bold text-2xl text-gray-900 dark:text-white">Formulir Pengusulan Hibah</h1>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Pengganti Google Form terintegrasi dengan peninjauan reviewer dan pencatatan akademik.</p>
                </div>

                @if(isset($periodeAktif))
                <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl p-3.5 sm:text-right shrink-0">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-800 dark:text-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Gelombang Dibuka
                    </span>
                    <p class="text-xs font-extrabold text-gray-900 dark:text-white mt-0.5">{{ $periodeAktif->nama_periode }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Batas: {{ $periodeAktif->tanggal_tutup->format('d M Y') }}</p>
                </div>
                @endif
            </div>

            <!-- Download Template Dokumen & Panduan -->
            <div class="mt-5 p-4 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200/70 dark:border-blue-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                        📥
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">Unduh Template Berkas Resmi</div>
                        <div class="text-[11px] text-gray-600 dark:text-gray-300">Gunakan format standar LPPM Mahardika sebelum mengunggah dokumen proposal &amp; RAB.</div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <a href="{{ asset('templates/Template_Proposal_Dasar_LPPM_Mahardika.docx') }}" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-blue-300 dark:border-blue-700 text-xs font-bold text-blue-700 dark:text-blue-300 hover:bg-blue-50 dark:hover:bg-slate-600 transition shadow-xs">
                        <span>📥 Download Template Proposal Dasar</span>
                    </a>
                    <a href="{{ asset('templates/Template_Anggaran_RAB_LPPM_Mahardika.xlsx') }}" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-emerald-300 dark:border-emerald-700 text-xs font-bold text-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-slate-600 transition shadow-xs">
                        <span>📥 Download Template Anggaran RAB</span>
                    </a>
                </div>
            </div>

            <!-- Identitas Pengusul -->
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl text-xs border border-gray-200/60 dark:border-gray-700">
                <div>
                    <span class="text-gray-500 dark:text-gray-400 block font-medium">Dosen Pengusul</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $dosen->nama ?? auth()->user()->name }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400 block font-medium">NIDN / NIP</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $dosen->nidn ?? '0400000001' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400 block font-medium">Program Studi</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $dosen->prodi ?? 'Fakultas Kesehatan' }}</span>
                </div>
            </div>
        </div>

        @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 dark:bg-red-950/40 dark:border-red-800 dark:text-red-200 text-xs space-y-1">
            <p class="font-bold">Terjadi kesalahan pada data yang diisi:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 dark:bg-red-950/40 dark:border-red-800 dark:text-red-200 text-xs font-semibold">
            {{ session('error') }}
        </div>
        @endif

        <!-- Form Pengajuan Proposal -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 shadow-sm transition-colors duration-200"
             x-data="{ 
                skemaPilihan: '{{ old('skema', 'Penelitian') }}',
                subKategori: '{{ old('sub_kategori', 'Dasar') }}'
             }">
            <form action="{{ route('dosen.proposal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="dosen_id" value="{{ $dosen->id ?? '' }}">

                <!-- Jenis Hibah & Skema Usulan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Dropdown Jenis Hibah -->
                    <div>
                        <label for="jenis_hibah" class="block text-xs font-bold text-gray-900 dark:text-white mb-2">
                            Jenis Hibah <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_hibah" name="jenis_hibah" required class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                            <option value="Internal LPPM" {{ old('jenis_hibah') == 'Internal LPPM' ? 'selected' : '' }}>🏛️ Internal LPPM</option>
                            <option value="Eksternal (BIMA Dikti)" {{ old('jenis_hibah') == 'Eksternal (BIMA Dikti)' ? 'selected' : '' }}>🌐 Eksternal (BIMA Dikti)</option>
                        </select>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Pilih sumber pendanaan atau skema penyelenggara hibah.</p>
                    </div>

                    <!-- Skema Usulan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-2">
                            Skema Usulan Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="relative flex items-center p-2.5 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700/50 border-gray-300 dark:border-gray-600 has-[:checked]:border-[#00295f] has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/30 has-[:checked]:ring-2 has-[:checked]:ring-[#00295f]">
                                <input type="radio" name="skema" value="Penelitian" x-model="skemaPilihan" {{ old('skema', 'Penelitian') == 'Penelitian' ? 'checked' : '' }} class="text-[#00295f] focus:ring-[#00295f]">
                                <div class="ml-2.5">
                                    <span class="block text-xs font-bold text-gray-900 dark:text-white">🔬 Penelitian</span>
                                </div>
                            </label>
                            <label class="relative flex items-center p-2.5 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700/50 border-gray-300 dark:border-gray-600 has-[:checked]:border-[#00295f] has-[:checked]:bg-blue-50/50 dark:has-[:checked]:bg-blue-900/30 has-[:checked]:ring-2 has-[:checked]:ring-[#00295f]">
                                <input type="radio" name="skema" value="Pengabdian" x-model="skemaPilihan" {{ old('skema') == 'Pengabdian' ? 'checked' : '' }} class="text-[#00295f] focus:ring-[#00295f]">
                                <div class="ml-2.5">
                                    <span class="block text-xs font-bold text-gray-900 dark:text-white">🤝 Pengabdian</span>
                                </div>
                            </label>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Fokus kegiatan riset keilmuan atau pemberdayaan masyarakat.</p>
                    </div>
                </div>

                <!-- Sub-Kategori Penelitian (Muncul jika Skema Penelitian dipilih) -->
                <div x-show="skemaPilihan === 'Penelitian'" x-transition class="p-4 rounded-xl bg-blue-50/50 dark:bg-slate-700/40 border border-blue-200 dark:border-blue-900/50">
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-2">
                        Sub-Kategori Penelitian <span class="text-blue-600">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="relative flex items-center p-3 border rounded-xl cursor-pointer bg-white dark:bg-slate-800 border-gray-300 dark:border-gray-600 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/30 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                            <input type="radio" name="sub_kategori" value="Dasar" {{ old('sub_kategori', 'Dasar') == 'Dasar' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-600">
                            <div class="ml-3">
                                <span class="block text-xs font-bold text-gray-900 dark:text-white">📘 Penelitian Dasar</span>
                                <span class="block text-[11px] text-gray-500 dark:text-gray-400">Pengembangan teori ilmiah, prinsip fundamental, &amp; pemodelan konsep.</span>
                            </div>
                        </label>
                        <label class="relative flex items-center p-3 border rounded-xl cursor-pointer bg-white dark:bg-slate-800 border-gray-300 dark:border-gray-600 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/30 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                            <input type="radio" name="sub_kategori" value="Terapan" {{ old('sub_kategori') == 'Terapan' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-600">
                            <div class="ml-3">
                                <span class="block text-xs font-bold text-gray-900 dark:text-white">🚀 Penelitian Terapan</span>
                                <span class="block text-[11px] text-gray-500 dark:text-gray-400">Implementasi praktis, prototipe produk, inovasi klinis/teknologi.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Judul Proposal -->
                <div>
                    <label for="judul" class="block text-xs font-bold text-gray-900 dark:text-white mb-1">
                        Judul Usulan Proposal <span class="text-red-500">*</span>
                    </label>
                    <textarea id="judul" name="judul" rows="3" required placeholder="Contoh: Analisis Efektivitas Edukasi Gizi Berbasis Aplikasi Ponsel Pintar Terhadap Pencegahan Stunting..." class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-slate-700 dark:border-gray-600 dark:text-white placeholder-gray-400 transition">{{ old('judul') }}</textarea>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Gunakan judul yang jelas, spesifik, dan memuat tujuan inti riset/pengabdian.</p>
                </div>

                <!-- Upload Dokumen Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    
                    <!-- File Dokumen Proposal -->
                    <div class="bg-gray-50 dark:bg-slate-700/40 p-4 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-[#00295f] dark:text-blue-300 flex items-center justify-center font-bold text-xs">
                                📄
                            </div>
                            <div>
                                <label for="file_proposal" class="block text-xs font-bold text-gray-900 dark:text-white">
                                    File Dokumen Proposal <span class="text-red-500">*</span>
                                </label>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400">Format PDF, DOC, DOCX (Maks. 5 MB)</span>
                            </div>
                        </div>
                        <input type="file" id="file_proposal" name="file_proposal" required accept=".pdf,.doc,.docx" class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#00295f] file:text-white hover:file:bg-blue-900 file:cursor-pointer cursor-pointer">
                    </div>

                    <!-- File Dokumen RAB -->
                    <div class="bg-gray-50 dark:bg-slate-700/40 p-4 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 flex items-center justify-center font-bold text-xs">
                                💰
                            </div>
                            <div>
                                <label for="file_rab" class="block text-xs font-bold text-gray-900 dark:text-white">
                                    File Rencana Anggaran Biaya (RAB) <span class="text-red-500">*</span>
                                </label>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400">Format PDF, DOC, DOCX (Maks. 5 MB)</span>
                            </div>
                        </div>
                        <input type="file" id="file_rab" name="file_rab" required accept=".pdf,.doc,.docx" class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 file:cursor-pointer cursor-pointer">
                    </div>

                </div>

                <!-- Tombol Submit & Persetujuan -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">
                        Dengan menekan tombol kirim, Anda menyatakan bahwa data dan berkas yang diajukan adalah orisinal.
                    </p>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('dosen.dashboard') }}" class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#00295f] hover:bg-blue-900 text-white text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Kirim Usulan Proposal</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
