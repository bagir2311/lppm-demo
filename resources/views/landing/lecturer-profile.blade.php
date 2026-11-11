@extends('layouts.app')

@section('title', $lecturer['name'] . ' - Profil Akademik LPPM')

@section('content')
<div class="min-h-screen bg-bg-alt dark:bg-bg-main/60 pt-28 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs Navigation -->
        <nav class="flex mb-8 text-xs font-semibold uppercase tracking-wider" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-text-disabled">
                <li class="inline-flex items-center">
                    <a href="{{ route('landing.index') }}" class="hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-text-disabled/60 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        <a href="{{ route('landing.index') }}#dosen" class="hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150">Direktori Dosen</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-text-disabled/60 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        <span class="text-text-main dark:text-navy-accent font-bold">{{ $lecturer['name'] }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Header Banner (Lecturer Hero Block) -->
        <div class="bg-white border border-border-main rounded-3xl p-6 sm:p-8 md:p-10 shadow-card dark:bg-dark-surface dark:border-dark-border mb-8 overflow-hidden relative">
            <div class="absolute right-0 top-0 opacity-5 pointer-events-none dark:opacity-10">
                <svg class="w-96 h-96 text-mahardika-navy dark:text-navy-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Avatar image container -->
                <div class="relative w-32 h-32 sm:w-36 sm:h-36 rounded-full overflow-hidden border-4 border-mahardika-navy/10 dark:border-dark-border shrink-0 shadow-floating">
                    <img src="{{ !empty($lecturer['avatar']) && !\Illuminate\Support\Str::startsWith($lecturer['avatar'], ['http://', 'https://', '/']) ? asset('storage/' . $lecturer['avatar']) : ($lecturer['avatar'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=256') }}" alt="{{ $lecturer['name'] }}" class="w-full h-full object-cover">
                </div>

                <!-- Identity Summary -->
                <div class="flex-1 text-center md:text-left space-y-4">
                    <div>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-2">
                            <span class="px-3 py-1 rounded bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent font-primary font-bold text-[10px] uppercase tracking-wider">Dosen Tetap</span>
                            <span class="px-3 py-1 rounded bg-success-green-pale text-success-green font-primary font-bold text-[10px] uppercase tracking-wider">Sektor Riset Aktif</span>
                        </div>
                        <h1 class="font-primary font-extrabold text-2xl sm:text-3xl text-text-main leading-tight">{{ $lecturer['name'] }}</h1>
                        <span class="text-xs text-text-secondary dark:text-dark-text-muted mt-1 inline-block font-semibold">NIDN: {{ $lecturer['nidn'] }}</span>
                    </div>

                    <div class="h-px bg-border-main dark:bg-dark-border"></div>

                    <!-- Statistics Bar -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl">
                        <div class="p-3 bg-bg-alt dark:bg-dark-surface-soft/60 rounded-2xl text-center border border-border-main/50 dark:border-dark-border/40">
                            <span class="block text-[10px] text-text-secondary dark:text-dark-text-muted uppercase font-bold">Publikasi</span>
                            <span class="font-primary font-extrabold text-xl text-mahardika-navy dark:text-navy-accent mt-0.5 block">{{ $lecturer['publications'] }}</span>
                        </div>
                        <div class="p-3 bg-bg-alt dark:bg-dark-surface-soft/60 rounded-2xl text-center border border-border-main/50 dark:border-dark-border/40">
                            <span class="block text-[10px] text-text-secondary dark:text-dark-text-muted uppercase font-bold">Total Sitasi</span>
                            <span class="font-primary font-extrabold text-xl text-gold-accent mt-0.5 block">{{ $lecturer['citations'] }}</span>
                        </div>
                        <div class="p-3 bg-bg-alt dark:bg-dark-surface-soft/60 rounded-2xl text-center border border-border-main/50 dark:border-dark-border/40">
                            <span class="block text-[10px] text-text-secondary dark:text-dark-text-muted uppercase font-bold">H-Index</span>
                            <span class="font-primary font-extrabold text-xl text-[#0891b2] mt-0.5 block">5</span>
                        </div>
                        <div class="p-3 bg-bg-alt dark:bg-dark-surface-soft/60 rounded-2xl text-center border border-border-main/50 dark:border-dark-border/40">
                            <span class="block text-[10px] text-text-secondary dark:text-dark-text-muted uppercase font-bold">SINTA Score</span>
                            <span class="font-primary font-extrabold text-xl text-alert-red mt-0.5 block">320</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid Partition -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Compact Metadata Details -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Academic Credentials -->
                <div class="bg-white border border-border-main rounded-3xl p-6 shadow-card dark:bg-dark-surface dark:border-dark-border">
                    <h3 class="font-primary font-bold text-sm text-text-main mb-4 uppercase tracking-wider border-b border-border-main dark:border-dark-border pb-2">Informasi Akademis</h3>
                    
                    <div class="space-y-4 font-body text-xs text-text-secondary dark:text-dark-text-muted">
                        <div class="flex flex-col space-y-1">
                            <span class="font-bold text-text-disabled uppercase text-[9px]">Fakultas</span>
                            <span class="text-text-main font-semibold dark:text-white">{{ $lecturer['faculty'] }}</span>
                        </div>
                        <div class="flex flex-col space-y-1">
                            <span class="font-bold text-text-disabled uppercase text-[9px]">Program Studi</span>
                            <span class="text-text-main font-semibold dark:text-white">{{ $lecturer['study_program'] }}</span>
                        </div>
                        <div class="flex flex-col space-y-1">
                            <span class="font-bold text-text-disabled uppercase text-[9px]">Fokus Bidang Keahlian</span>
                            <span class="px-3 py-2 rounded-xl bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent font-semibold leading-relaxed">
                                {{ $lecturer['expertise'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Academic Profiles External Portals -->
                <div class="bg-white border border-border-main rounded-3xl p-6 shadow-card dark:bg-dark-surface dark:border-dark-border">
                    <h3 class="font-primary font-bold text-sm text-text-main mb-4 uppercase tracking-wider border-b border-border-main dark:border-dark-border pb-2">Index Repositori</h3>
                    
                    <div class="space-y-3 font-body text-xs">
                        <a href="https://sinta.kemdikbud.go.id/authors/detail?id=mock" target="_blank" class="flex items-center justify-between p-3 rounded-2xl bg-bg-alt hover:bg-mahardika-navy-pale dark:bg-dark-surface-soft dark:hover:bg-dark-surface-soft/80 border border-border-main/50 dark:border-dark-border/40 text-text-main transition-colors duration-150">
                            <span class="font-bold text-alert-red flex items-center">
                                <span class="w-2.5 h-2.5 rounded-full bg-alert-red mr-2"></span> SINTA Profile
                            </span>
                            <svg class="w-4 h-4 text-text-disabled" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 00-2 2h14a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                        <a href="https://scholar.google.com/citations?user=mock" target="_blank" class="flex items-center justify-between p-3 rounded-2xl bg-bg-alt hover:bg-mahardika-navy-pale dark:bg-dark-surface-soft dark:hover:bg-dark-surface-soft/80 border border-border-main/50 dark:border-dark-border/40 text-text-main transition-colors duration-150">
                            <span class="font-bold text-[#0284c7] flex items-center">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#0284c7] mr-2"></span> Google Scholar
                            </span>
                            <svg class="w-4 h-4 text-text-disabled" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 00-2 2h14a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                        <a href="https://scopus.com/authid/detail.uri?authorId=mock" target="_blank" class="flex items-center justify-between p-3 rounded-2xl bg-bg-alt hover:bg-mahardika-navy-pale dark:bg-dark-surface-soft dark:hover:bg-dark-surface-soft/80 border border-border-main/50 dark:border-dark-border/40 text-text-main transition-colors duration-150">
                            <span class="font-bold text-success-green flex items-center">
                                <span class="w-2.5 h-2.5 rounded-full bg-success-green mr-2"></span> Scopus Profile
                            </span>
                            <svg class="w-4 h-4 text-text-disabled" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 00-2 2h14a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Research, Service and Publications lists -->
            <div class="lg:col-span-8 space-y-8" x-data="{ tab: 'penelitian' }">
                
                <!-- Tab Headers Selection -->
                <div class="bg-white border border-border-main rounded-3xl p-2 shadow-card dark:bg-dark-surface dark:border-dark-border flex">
                    <button @click="tab = 'penelitian'" 
                            :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent shadow-subtle': tab === 'penelitian', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': tab !== 'penelitian' }" 
                            class="flex-1 py-3 px-4 font-primary font-bold text-xs uppercase tracking-wider rounded-2xl transition-all duration-200">
                        Riwayat Penelitian
                    </button>
                    <button @click="tab = 'pengabdian'" 
                            :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent shadow-subtle': tab === 'pengabdian', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': tab !== 'pengabdian' }" 
                            class="flex-1 py-3 px-4 font-primary font-bold text-xs uppercase tracking-wider rounded-2xl transition-all duration-200">
                        Riwayat Pengabdian (PkM)
                    </button>
                    <button @click="tab = 'publikasi'" 
                            :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent shadow-subtle': tab === 'publikasi', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': tab !== 'publikasi' }" 
                            class="flex-1 py-3 px-4 font-primary font-bold text-xs uppercase tracking-wider rounded-2xl transition-all duration-200">
                        Daftar Publikasi
                    </button>
                </div>

                <!-- Tab 1 Panel: Riwayat Penelitian -->
                <div x-show="tab === 'penelitian'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2">
                    <div class="bg-white border border-border-main rounded-3xl overflow-hidden shadow-card dark:bg-dark-surface dark:border-dark-border">
                        <div class="p-6 border-b border-border-main dark:border-dark-border flex justify-between items-center">
                            <h3 class="font-primary font-bold text-base text-text-main">Log Penelitian Terbuka</h3>
                            <span class="px-2.5 py-1 rounded bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent text-[10px] font-bold uppercase tracking-wider">
                                {{ count($lecturer['research_history']) }} Hibah
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-bg-alt border-b border-border-main/80 text-[10px] uppercase font-bold text-text-secondary dark:bg-dark-surface-soft/40 dark:border-dark-border">
                                        <th class="p-4 w-20">Tahun</th>
                                        <th class="p-4">Judul Riset</th>
                                        <th class="p-4 w-28">Peran</th>
                                        <th class="p-4 w-32">Sumber Dana</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-main/50 dark:divide-dark-border/40 font-body text-xs text-text-secondary dark:text-dark-text-muted">
                                    @forelse($lecturer['research_history'] as $res)
                                    <tr class="hover:bg-mahardika-navy-pale/30 dark:hover:bg-dark-surface-soft/20 transition-colors duration-150">
                                        <td class="p-4 font-bold text-text-main">{{ $res['year'] }}</td>
                                        <td class="p-4 font-semibold text-text-main leading-normal">{{ $res['title'] }}</td>
                                        <td class="p-4">
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $res['role'] == 'Ketua' ? 'bg-mahardika-navy-pale text-mahardika-navy' : 'bg-success-green-pale text-success-green' }}">
                                                {{ $res['role'] }}
                                            </span>
                                        </td>
                                        <td class="p-4 font-medium">{{ $res['funding_source'] ?? 'Hibah Internal Mahardika' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-text-disabled">Belum ada riwayat penelitian tercatat.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab 2 Panel: Riwayat Pengabdian -->
                <div x-show="tab === 'pengabdian'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" style="display: none;">
                    <div class="bg-white border border-border-main rounded-3xl overflow-hidden shadow-card dark:bg-dark-surface dark:border-dark-border">
                        <div class="p-6 border-b border-border-main dark:border-dark-border flex justify-between items-center">
                            <h3 class="font-primary font-bold text-base text-text-main">Log Pengabdian Kepada Masyarakat</h3>
                            <span class="px-2.5 py-1 rounded bg-success-green-pale text-success-green text-[10px] font-bold uppercase tracking-wider">
                                {{ count($lecturer['service_history']) }} Hibah
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-bg-alt border-b border-border-main/80 text-[10px] uppercase font-bold text-text-secondary dark:bg-dark-surface-soft/40 dark:border-dark-border">
                                        <th class="p-4 w-20">Tahun</th>
                                        <th class="p-4">Skema Program Pengmas</th>
                                        <th class="p-4 w-28">Peran</th>
                                        <th class="p-4 w-32">Sumber Dana</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-main/50 dark:divide-dark-border/40 font-body text-xs text-text-secondary dark:text-dark-text-muted">
                                    @forelse($lecturer['service_history'] as $ser)
                                    <tr class="hover:bg-success-green-pale/30 dark:hover:bg-dark-surface-soft/20 transition-colors duration-150">
                                        <td class="p-4 font-bold text-text-main">{{ $ser['year'] }}</td>
                                        <td class="p-4 font-semibold text-text-main leading-normal">{{ $ser['title'] }}</td>
                                        <td class="p-4">
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-success-green text-white">
                                                {{ $ser['role'] }}
                                            </span>
                                        </td>
                                        <td class="p-4 font-medium">{{ $ser['funding_source'] ?? 'Masyarakat / Mandiri' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-text-disabled">Belum ada riwayat pengabdian tercatat.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab 3 Panel: Daftar Publikasi -->
                <div x-show="tab === 'publikasi'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" style="display: none;">
                    <div class="bg-white border border-border-main rounded-3xl p-6 shadow-card dark:bg-dark-surface dark:border-dark-border">
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-border-main dark:border-dark-border">
                            <h3 class="font-primary font-bold text-base text-text-main">Publikasi Ilmiah Terpilih</h3>
                            <span class="px-2.5 py-1 rounded bg-[#0284c7]/10 text-[#0284c7] text-[10px] font-bold uppercase tracking-wider">
                                SINTA & Scopus
                            </span>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($lecturer['selected_publications'] ?? [] as $pub)
                            <div class="p-4 rounded-2xl bg-bg-alt hover:bg-mahardika-navy-pale/20 dark:bg-dark-surface-soft/60 dark:hover:bg-dark-surface-soft/80 border border-border-main/50 dark:border-dark-border/40 flex items-start space-x-3.5 transition-colors duration-150">
                                <!-- Doc vector icon -->
                                <div class="w-9 h-9 rounded-lg bg-mahardika-navy-pale dark:bg-dark-surface-soft flex items-center justify-center text-mahardika-navy dark:text-navy-accent shrink-0 mt-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                                <div class="flex-1 space-y-1.5">
                                    <p class="font-body text-xs font-semibold text-text-main leading-relaxed dark:text-white">{{ $pub['title'] }}</p>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] text-text-disabled">
                                        <span class="font-bold text-success-green flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a.75.75 0 00-.708-.523H4.5a.75.75 0 000 1.5h.35l.867 4.335a.75.75 0 00.708.523h4.15a.75.75 0 00.708-.523l.867-4.335h.35a.75.75 0 000-1.5h-1.059a.75.75 0 00-.708.523L9.5 6.455 7.633 3.455z" clip-rule="evenodd" /></svg>
                                            {{ $pub['journal'] ?? 'Jurnal Kesehatan' }}
                                        </span>
                                        <span>•</span>
                                        <span class="font-bold text-mahardika-navy dark:text-navy-accent">Sitasi: {{ $pub['citation'] ?? '0' }}</span>
                                        <span>•</span>
                                        <span class="font-semibold text-text-disabled">Tahun {{ $pub['year'] ?? '2025' }}</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center p-8 text-text-disabled">Belum ada riwayat publikasi ilmiah terpilih.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
