@extends('layouts.app')

@section('title', 'Dasbor Utama Dosen (Portal 4 Pilar) - LPPM Universitas Insan Mahardika')

@section('content')
<div class="py-8 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-200" 
     x-data="{ 
         activeMenu: '{{ request()->query('tab', 'penelitian') }}',
         showDetailModal: false,
         selectedProposal: null
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- 1. Header Salam & Info Portal -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-200">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span>Portal Satu Pintu Dosen LPPM Mahardika</span>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Dasbor Akademik Dosen
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1">
                    Selamat datang, <span class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'Bapak/Ibu Dosen' }}</span>. Kelola seluruh aktivitas 4 Pilar LPPM: Penelitian, Pengabdian, Persuratan, dan HKI.
                </p>
            </div>
            <div class="flex items-center gap-2 text-xs font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-slate-700/60 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 self-start sm:self-auto shrink-0 transition-colors duration-200">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center justify-between shadow-sm transition-colors duration-200">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:opacity-75 font-bold ml-4">✕</button>
        </div>
        @endif

        <!-- 2. KOTAK MENU UTAMA (ARSITEKTUR 4 PILAR GRID TAILWIND) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Pilar 1: PENELITIAN -->
            <button type="button"
                    @click="activeMenu = 'penelitian'"
                    :class="activeMenu === 'penelitian' 
                        ? 'ring-2 ring-blue-600 dark:ring-blue-400 bg-blue-50/90 dark:bg-blue-950/40 border-blue-500 shadow-md scale-[1.01]' 
                        : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-slate-600 hover:shadow-sm'"
                    class="p-5 rounded-2xl border text-left transition-all duration-200 cursor-pointer flex flex-col justify-between group">
                <div class="flex items-center justify-between w-full">
                    <span class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🔬
                    </span>
                    <span :class="activeMenu === 'penelitian' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300'"
                          class="text-xs px-2.5 py-0.5 rounded-full font-bold transition-colors">
                        {{ $penelitianProposals->count() }} Usulan
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                        <span>1. PENELITIAN</span>
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">
                        Usulan riset dasar/terapan, penelaahan reviewer, &amp; LPJ kegiatan.
                    </p>
                </div>
            </button>

            <!-- Pilar 2: PENGABDIAN -->
            <button type="button"
                    @click="activeMenu = 'pengabdian'"
                    :class="activeMenu === 'pengabdian' 
                        ? 'ring-2 ring-emerald-600 dark:ring-emerald-400 bg-emerald-50/90 dark:bg-emerald-950/40 border-emerald-500 shadow-md scale-[1.01]' 
                        : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-slate-600 hover:shadow-sm'"
                    class="p-5 rounded-2xl border text-left transition-all duration-200 cursor-pointer flex flex-col justify-between group">
                <div class="flex items-center justify-between w-full">
                    <span class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🤝
                    </span>
                    <span :class="activeMenu === 'pengabdian' ? 'bg-emerald-600 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300'"
                          class="text-xs px-2.5 py-0.5 rounded-full font-bold transition-colors">
                        {{ $pengabdianProposals->count() }} Usulan
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                        <span>2. PENGABDIAN</span>
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">
                        Program pengabdian masyarakat, desa binaan, &amp; LPJ kemitraan.
                    </p>
                </div>
            </button>

            <!-- Pilar 3: LAYANAN PERSURATAN -->
            <button type="button"
                    @click="activeMenu = 'persuratan'"
                    :class="activeMenu === 'persuratan' 
                        ? 'ring-2 ring-purple-600 dark:ring-purple-400 bg-purple-50/90 dark:bg-purple-950/40 border-purple-500 shadow-md scale-[1.01]' 
                        : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-slate-600 hover:shadow-sm'"
                    class="p-5 rounded-2xl border text-left transition-all duration-200 cursor-pointer flex flex-col justify-between group">
                <div class="flex items-center justify-between w-full">
                    <span class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        ✉️
                    </span>
                    <span :class="activeMenu === 'persuratan' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300'"
                          class="text-xs px-2.5 py-0.5 rounded-full font-bold transition-colors">
                        {{ $surats->count() }} Surat
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                        <span>3. PERSURATAN</span>
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">
                        Surat izin penelitian, surat tugas, barcode &amp; cetak PDF resmi.
                    </p>
                </div>
            </button>

            <!-- Pilar 4: HKI -->
            <button type="button"
                    @click="activeMenu = 'haki'"
                    :class="activeMenu === 'haki' 
                        ? 'ring-2 ring-amber-500 dark:ring-amber-400 bg-amber-50/90 dark:bg-amber-950/40 border-amber-500 shadow-md scale-[1.01]' 
                        : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-gray-700 hover:border-amber-300 dark:hover:border-slate-600 hover:shadow-sm'"
                    class="p-5 rounded-2xl border text-left transition-all duration-200 cursor-pointer flex flex-col justify-between group">
                <div class="flex items-center justify-between w-full">
                    <span class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        💡
                    </span>
                    <span :class="activeMenu === 'haki' ? 'bg-amber-500 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300'"
                          class="text-xs px-2.5 py-0.5 rounded-full font-bold transition-colors">
                        {{ $hakiSubmissions->count() }} HKI
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                        <span>4. HKI</span>
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">
                        Pencatatan hak cipta, paten karya dosen, &amp; sertifikat resmi.
                    </p>
                </div>
            </button>

        </div>

        <!-- 3. KONTEN TAB NESTED BERDASARKAN PILAR AKTIF -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors duration-200">
            
            <!-- ========================================== -->
            <!-- 1. KONTEN PENELITIAN (activeMenu === 'penelitian') -->
            <!-- ========================================== -->
            <div x-show="activeMenu === 'penelitian'" 
                 x-transition:enter="transition ease-out duration-150" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100">
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-slate-800/60 transition-colors duration-200">
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wider">
                            <span>🔬 Pilar 1: Kegiatan Penelitian</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white mt-0.5">Daftar Usulan Penelitian Dosen</h2>
                        <p class="text-[11px] text-gray-600 dark:text-gray-300">Pengajuan proposal riset, pemantauan reviewer, dan pelaporan LPJ berkala.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('dosen.proposal.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Ajuan Proposal Baru</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-800/90 text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4 w-12 text-center">NO</th>
                                <th class="py-3 px-4">JUDUL USULAN &amp; SUB-SKEMA</th>
                                <th class="py-3 px-4">JENIS HIBAH</th>
                                <th class="py-3 px-4">TANGGAL</th>
                                <th class="py-3 px-4">STATUS</th>
                                <th class="py-3 px-4 text-center">AKSI &amp; LAPORAN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                            @forelse($penelitianProposals as $proposal)
                            @php
                                $statusStr = strtolower($proposal->status ?? '');
                                $statusBadge = match($statusStr) {
                                    'didanai' => ['bg' => 'bg-emerald-100 dark:bg-emerald-950/60', 'text' => 'text-emerald-800 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800', 'label' => 'Didanai'],
                                    'tidak didanai', 'ditolak', 'rejected' => ['bg' => 'bg-rose-100 dark:bg-rose-950/60', 'text' => 'text-rose-800 dark:text-rose-300', 'border' => 'border-rose-200 dark:border-rose-800', 'label' => 'Tidak Didanai'],
                                    'diterima', 'accepted', 'approved' => ['bg' => 'bg-teal-100 dark:bg-teal-950/60', 'text' => 'text-teal-800 dark:text-teal-300', 'border' => 'border-teal-200 dark:border-teal-800', 'label' => 'Diterima'],
                                    'di-review', 'in_review', 'review' => ['bg' => 'bg-blue-100 dark:bg-blue-950/60', 'text' => 'text-blue-800 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-800', 'label' => 'Di-review'],
                                    'revisi' => ['bg' => 'bg-amber-100 dark:bg-amber-950/60', 'text' => 'text-amber-800 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800', 'label' => 'Perlu Revisi'],
                                    default => ['bg' => 'bg-yellow-100 dark:bg-yellow-950/60', 'text' => 'text-yellow-800 dark:text-yellow-300', 'border' => 'border-yellow-200 dark:border-yellow-800', 'label' => 'Diajukan'],
                                };
                                $isDidanai = in_array($statusStr, ['didanai', 'approved', 'diterima', 'accepted']);
                            @endphp
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold max-w-md">
                                    <div class="text-gray-900 dark:text-white font-bold leading-snug">{{ $proposal->judul }}</div>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            Penelitian {{ $proposal->sub_kategori ? '('.$proposal->sub_kategori.')' : '' }}
                                        </span>
                                    </div>
                                    @if($proposal->catatan_reviewer)
                                    <div class="mt-1.5 p-2 bg-amber-50 dark:bg-amber-950/40 rounded-lg border border-amber-200 dark:border-amber-800/70 text-[11px] text-amber-900 dark:text-amber-200">
                                        <span class="font-bold">Catatan Reviewer:</span> {{ $proposal->catatan_reviewer }}
                                    </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-200">
                                        {{ $proposal->jenis_hibah ?? 'Internal LPPM' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                    {{ $proposal->created_at ? $proposal->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold inline-flex items-center border {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} {{ $statusBadge['border'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-current"></span>
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Tombol Detail/Edit -->
                                        <button type="button"
                                                @click="selectedProposal = {
                                                    id: '{{ $proposal->id }}',
                                                    judul: '{{ addslashes($proposal->judul) }}',
                                                    skema: 'Penelitian',
                                                    sub_kategori: '{{ addslashes($proposal->sub_kategori ?? 'Dasar') }}',
                                                    jenis_hibah: '{{ addslashes($proposal->jenis_hibah ?? 'Internal LPPM') }}',
                                                    status: '{{ addslashes($statusBadge['label']) }}',
                                                    statusRaw: '{{ addslashes($statusStr) }}',
                                                    isDidanai: {{ $isDidanai ? 'true' : 'false' }},
                                                    tanggal: '{{ $proposal->created_at ? $proposal->created_at->format('d M Y') : '-' }}',
                                                    catatan: '{{ addslashes($proposal->catatan_reviewer ?? '') }}',
                                                    file_proposal: '{{ $proposal->file_proposal ? asset('storage/' . $proposal->file_proposal) : '' }}',
                                                    file_rab: '{{ $proposal->file_rab ? asset('storage/' . $proposal->file_rab) : '' }}',
                                                    lpj_url: '{{ route('dosen.laporan.create', ['proposal_id' => $proposal->id]) }}'
                                                }; showDetailModal = true;"
                                                class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 transition shadow-xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 mr-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail/Edit
                                        </button>

                                        <!-- Tombol Unggah Laporan & LPJ (Peleburan LPJ - Muncul jika status Didanai) -->
                                        @if($isDidanai)
                                        <a href="{{ route('dosen.laporan.create', ['proposal_id' => $proposal->id]) }}" 
                                           class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-xs">
                                            <span class="mr-1">📂</span> Unggah Laporan &amp; LPJ
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="text-3xl mb-2">🔬</div>
                                    <div class="font-bold text-sm text-gray-900 dark:text-white">Belum Ada Usulan Penelitian</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">Klik tombol "+ Ajuan Proposal Baru" di atas untuk mengirimkan usulan riset baru.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 2. KONTEN PENGABDIAN (activeMenu === 'pengabdian') -->
            <!-- ========================================== -->
            <div x-show="activeMenu === 'pengabdian'" 
                 x-transition:enter="transition ease-out duration-150" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 style="display: none;">
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-slate-800/60 transition-colors duration-200">
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">
                            <span>🤝 Pilar 2: Kegiatan Pengabdian kepada Masyarakat</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white mt-0.5">Daftar Usulan Pengabdian Masyarakat (PKM)</h2>
                        <p class="text-[11px] text-gray-600 dark:text-gray-300">Program aksi kemasyarakatan, pendampingan mitra/UMKM, dan pelaporan luaran kemitraan.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('dosen.proposal.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Ajuan Proposal Baru</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-800/90 text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4 w-12 text-center">NO</th>
                                <th class="py-3 px-4">JUDUL USULAN PKM</th>
                                <th class="py-3 px-4">JENIS HIBAH</th>
                                <th class="py-3 px-4">TANGGAL</th>
                                <th class="py-3 px-4">STATUS</th>
                                <th class="py-3 px-4 text-center">AKSI &amp; LAPORAN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                            @forelse($pengabdianProposals as $proposal)
                            @php
                                $statusStr = strtolower($proposal->status ?? '');
                                $statusBadge = match($statusStr) {
                                    'didanai' => ['bg' => 'bg-emerald-100 dark:bg-emerald-950/60', 'text' => 'text-emerald-800 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800', 'label' => 'Didanai'],
                                    'tidak didanai', 'ditolak', 'rejected' => ['bg' => 'bg-rose-100 dark:bg-rose-950/60', 'text' => 'text-rose-800 dark:text-rose-300', 'border' => 'border-rose-200 dark:border-rose-800', 'label' => 'Tidak Didanai'],
                                    'diterima', 'accepted', 'approved' => ['bg' => 'bg-teal-100 dark:bg-teal-950/60', 'text' => 'text-teal-800 dark:text-teal-300', 'border' => 'border-teal-200 dark:border-teal-800', 'label' => 'Diterima'],
                                    'di-review', 'in_review', 'review' => ['bg' => 'bg-blue-100 dark:bg-blue-950/60', 'text' => 'text-blue-800 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-800', 'label' => 'Di-review'],
                                    'revisi' => ['bg' => 'bg-amber-100 dark:bg-amber-950/60', 'text' => 'text-amber-800 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800', 'label' => 'Perlu Revisi'],
                                    default => ['bg' => 'bg-yellow-100 dark:bg-yellow-950/60', 'text' => 'text-yellow-800 dark:text-yellow-300', 'border' => 'border-yellow-200 dark:border-yellow-800', 'label' => 'Diajukan'],
                                };
                                $isDidanai = in_array($statusStr, ['didanai', 'approved', 'diterima', 'accepted']);
                            @endphp
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold max-w-md">
                                    <div class="text-gray-900 dark:text-white font-bold leading-snug">{{ $proposal->judul }}</div>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            Pengabdian Masyarakat
                                        </span>
                                    </div>
                                    @if($proposal->catatan_reviewer)
                                    <div class="mt-1.5 p-2 bg-amber-50 dark:bg-amber-950/40 rounded-lg border border-amber-200 dark:border-amber-800/70 text-[11px] text-amber-900 dark:text-amber-200">
                                        <span class="font-bold">Catatan Reviewer:</span> {{ $proposal->catatan_reviewer }}
                                    </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-200">
                                        {{ $proposal->jenis_hibah ?? 'Internal LPPM' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                    {{ $proposal->created_at ? $proposal->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold inline-flex items-center border {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} {{ $statusBadge['border'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-current"></span>
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Tombol Detail/Edit -->
                                        <button type="button"
                                                @click="selectedProposal = {
                                                    id: '{{ $proposal->id }}',
                                                    judul: '{{ addslashes($proposal->judul) }}',
                                                    skema: 'Pengabdian',
                                                    sub_kategori: '-',
                                                    jenis_hibah: '{{ addslashes($proposal->jenis_hibah ?? 'Internal LPPM') }}',
                                                    status: '{{ addslashes($statusBadge['label']) }}',
                                                    statusRaw: '{{ addslashes($statusStr) }}',
                                                    isDidanai: {{ $isDidanai ? 'true' : 'false' }},
                                                    tanggal: '{{ $proposal->created_at ? $proposal->created_at->format('d M Y') : '-' }}',
                                                    catatan: '{{ addslashes($proposal->catatan_reviewer ?? '') }}',
                                                    file_proposal: '{{ $proposal->file_proposal ? asset('storage/' . $proposal->file_proposal) : '' }}',
                                                    file_rab: '{{ $proposal->file_rab ? asset('storage/' . $proposal->file_rab) : '' }}',
                                                    lpj_url: '{{ route('dosen.laporan.create', ['proposal_id' => $proposal->id]) }}'
                                                }; showDetailModal = true;"
                                                class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 transition shadow-xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5 mr-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail/Edit
                                        </button>

                                        <!-- Tombol Unggah Laporan & LPJ (Peleburan LPJ - Muncul jika status Didanai) -->
                                        @if($isDidanai)
                                        <a href="{{ route('dosen.laporan.create', ['proposal_id' => $proposal->id]) }}" 
                                           class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-xs">
                                            <span class="mr-1">📂</span> Unggah Laporan &amp; LPJ
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="text-3xl mb-2">🤝</div>
                                    <div class="font-bold text-sm text-gray-900 dark:text-white">Belum Ada Usulan Pengabdian</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">Klik tombol "+ Ajuan Proposal Baru" di atas untuk mengirimkan kegiatan PKM baru.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 3. KONTEN PERSURATAN (activeMenu === 'persuratan') -->
            <!-- ========================================== -->
            <div x-show="activeMenu === 'persuratan'" 
                 x-transition:enter="transition ease-out duration-150" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 style="display: none;">
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-slate-800/60 transition-colors duration-200">
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 dark:text-purple-300 uppercase tracking-wider">
                            <span>✉️ Pilar 3: Layanan Persuratan Akademik</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white mt-0.5">Daftar Permohonan Surat Izin &amp; Tugas</h2>
                        <p class="text-[11px] text-gray-600 dark:text-gray-300">Surat izin penelitian, surat tugas dosen, verifikasi barcode &amp; unduh PDF resmi.</p>
                    </div>
                    <a href="{{ url('/surat') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm transition shrink-0 self-start sm:self-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Buat Permohonan Surat</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-800/90 text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4 w-12 text-center">NO</th>
                                <th class="py-3 px-4">JENIS SURAT</th>
                                <th class="py-3 px-4">NOMOR SURAT</th>
                                <th class="py-3 px-4">TANGGAL</th>
                                <th class="py-3 px-4">STATUS</th>
                                <th class="py-3 px-4 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                            @forelse($surats as $surat)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold">
                                    <div class="text-gray-900 dark:text-white font-bold leading-snug">{{ $surat->jenis_surat }}</div>
                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ $surat->keperluan ?? 'Keperluan Akademik' }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono text-[11px] text-gray-700 dark:text-gray-300">
                                    {{ $surat->nomor_surat ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                    {{ $surat->created_at ? $surat->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if(strtolower($surat->status) === 'approved')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 inline-flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @elseif(strtolower($surat->status) === 'rejected')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800 inline-flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-950/60 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800 inline-flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-yellow-500"></span>
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if(strtolower($surat->status) === 'approved')
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('surat.cetak', $surat->id) }}" target="_blank" class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border dark:border-emerald-800 transition" title="Cetak Format Word A4">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Cetak A4
                                        </a>
                                        <a href="{{ route('surat.pdf', $surat->id) }}" target="_blank" class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/60 dark:text-purple-300 dark:border dark:border-purple-800 transition" title="Unduh PDF Resmi">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/></svg>
                                            PDF
                                        </a>
                                    </div>
                                    @else
                                    <span class="text-gray-400 italic text-[11px]">Proses Validasi</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="text-3xl mb-2">✉️</div>
                                    <div class="font-bold text-sm text-gray-900 dark:text-white">Belum Ada Permohonan Surat</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">Gunakan tombol "+ Buat Permohonan Surat" untuk mengajukan permohonan surat izin atau tugas baru.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 4. KONTEN HKI (activeMenu === 'haki') -->
            <!-- ========================================== -->
            <div x-show="activeMenu === 'haki'" 
                 x-transition:enter="transition ease-out duration-150" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 style="display: none;">
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-slate-800/60 transition-colors duration-200">
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 dark:text-amber-300 uppercase tracking-wider">
                            <span>💡 Pilar 4: Sentra Hak Kekayaan Intelektual</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white mt-0.5">Riwayat Pengajuan Pencatatan HKI</h2>
                        <p class="text-[11px] text-gray-600 dark:text-gray-300">Pendaftaran hak cipta, paten karya ilmiah/teknologi, dan verifikasi sertifikat resmi.</p>
                    </div>
                    <a href="{{ route('dosen.haki.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-sm transition shrink-0 self-start sm:self-auto cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Ajukan HKI Baru</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-slate-800/90 text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4 w-12 text-center">NO</th>
                                <th class="py-3 px-4">JUDUL CIPTAAN &amp; KETUA</th>
                                <th class="py-3 px-4">JENIS KARYA</th>
                                <th class="py-3 px-4">TANGGAL</th>
                                <th class="py-3 px-4">STATUS</th>
                                <th class="py-3 px-4 text-center">DOKUMEN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                            @forelse($hakiSubmissions as $hki)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="py-3.5 px-4 text-center font-bold text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold max-w-md">
                                    <div class="text-gray-900 dark:text-white font-bold leading-snug">{{ $hki->judul_ciptaan }}</div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                        Ketua: <span class="font-bold text-gray-700 dark:text-gray-300">{{ $hki->nama_ketua }}</span>
                                        @if($hki->nama_anggota)
                                        | Anggota: {{ $hki->nama_anggota }}
                                        @endif
                                    </div>
                                    @if($hki->catatan_admin)
                                    <div class="mt-1.5 p-2 bg-amber-50 dark:bg-amber-950/40 rounded-lg border border-amber-200 dark:border-amber-800/70 text-[11px] text-amber-900 dark:text-amber-200">
                                        <span class="font-bold">Catatan Sentra HKI:</span> {{ $hki->catatan_admin }}
                                    </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        {{ $hki->jenis_ciptaan ?? 'Hak Cipta' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                    {{ $hki->created_at ? $hki->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @php
                                        $hkiStatus = strtolower($hki->status ?? 'diajukan');
                                    @endphp
                                    @if(in_array($hkiStatus, ['disetujui', 'approved', 'granted']))
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 inline-flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @elseif(in_array($hkiStatus, ['ditolak', 'rejected']))
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800 inline-flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @elseif(in_array($hkiStatus, ['pemeriksaan', 'in_review']))
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800 inline-flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-blue-500"></span>
                                            Pemeriksaan
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800 inline-flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-amber-500"></span>
                                            {{ $hki->status ?? 'Diajukan' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap space-x-1">
                                    @if($hki->file_lampiran_karya)
                                    <a href="{{ asset($hki->file_lampiran_karya) }}" target="_blank" class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 dark:border dark:border-amber-800 transition" title="Unduh Berkas Karya">
                                        Karya
                                    </a>
                                    @endif
                                    @if($hki->file_ktp)
                                    <a href="{{ asset($hki->file_ktp) }}" target="_blank" class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:text-gray-300 transition" title="KTP">
                                        KTP
                                    </a>
                                    @endif
                                    @if($hki->file_sertifikat)
                                    <a href="{{ asset($hki->file_sertifikat) }}" target="_blank" class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 transition" title="Sertifikat Resmi">
                                        Sertifikat
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="text-3xl mb-2">💡</div>
                                    <div class="font-bold text-sm text-gray-900 dark:text-white">Belum Ada Pengajuan HKI</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300 mt-1">Gunakan tombol "+ Ajukan HKI Baru" untuk mendaftarkan ciptaan atau karya Anda ke Sentra HKI LPPM.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- 4. MODAL DETAIL/EDIT PROPOSAL (ALPINE.JS) -->
    <div x-show="showDetailModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;"
         @keydown.escape.window="showDetailModal = false">
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-2xl max-w-xl w-full p-6 text-left relative transition-colors duration-200"
             @click.outside="showDetailModal = false">
            
            <!-- Modal Header -->
            <div class="flex items-start justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/70 dark:text-blue-300 border border-blue-200 dark:border-blue-800 inline-block mb-1"
                          x-text="'Skema ' + (selectedProposal?.skema || '') + ' - ' + (selectedProposal?.sub_kategori || '')"></span>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-snug" x-text="selectedProposal?.judul"></h3>
                </div>
                <button type="button" @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="py-4 space-y-4 text-xs">
                <div class="grid grid-cols-2 gap-3 bg-gray-50 dark:bg-slate-700/50 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-[10px] uppercase font-bold">Jenis Hibah</span>
                        <span class="font-semibold text-gray-900 dark:text-white" x-text="selectedProposal?.jenis_hibah"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-[10px] uppercase font-bold">Tanggal Pengajuan</span>
                        <span class="font-semibold text-gray-900 dark:text-white" x-text="selectedProposal?.tanggal"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-[10px] uppercase font-bold">Status Verifikasi</span>
                        <span class="font-bold text-gray-900 dark:text-white" x-text="selectedProposal?.status"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-[10px] uppercase font-bold">Hak Unggah LPJ</span>
                        <span class="font-bold" :class="selectedProposal?.isDidanai ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'"
                              x-text="selectedProposal?.isDidanai ? '✓ Diperbolehkan' : 'Belum (Menunggu Didanai)'"></span>
                    </div>
                </div>

                <!-- Catatan Reviewer jika ada -->
                <template x-if="selectedProposal?.catatan">
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-800/70">
                        <span class="font-bold text-amber-900 dark:text-amber-300 block mb-0.5">Catatan Tim Reviewer / Admin:</span>
                        <p class="text-amber-800 dark:text-amber-200" x-text="selectedProposal?.catatan"></p>
                    </div>
                </template>

                <!-- Berkas Usulan -->
                <div class="space-y-2">
                    <span class="font-bold text-gray-700 dark:text-gray-300 block">Dokumen Berkas Pengusul:</span>
                    <div class="flex flex-wrap gap-2">
                        <template x-if="selectedProposal?.file_proposal">
                            <a :href="selectedProposal?.file_proposal" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800 font-semibold transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/></svg>
                                <span>Unduh Naskah Proposal</span>
                            </a>
                        </template>
                        <template x-if="selectedProposal?.file_rab">
                            <a :href="selectedProposal?.file_rab" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 font-semibold transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/></svg>
                                <span>Unduh Rincian RAB</span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <template x-if="selectedProposal?.isDidanai">
                        <a :href="selectedProposal?.lpj_url" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
                            <span>📂 Lanjut Unggah Laporan &amp; LPJ</span>
                        </a>
                    </template>
                </div>
                <button type="button" @click="showDetailModal = false"
                        class="px-4 py-2 rounded-xl text-xs font-semibold bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
