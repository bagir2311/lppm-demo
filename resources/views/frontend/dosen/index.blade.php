@extends('layouts.app')

@section('title', 'Direktori Dosen & Peneliti - LPPM Universitas Insan Mahardika')

@section('content')
<div class="min-h-screen bg-bg-alt dark:bg-bg-main/60 pt-28 pb-20" x-data="{ search: '', facultyFilter: 'semua' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Breadcrumbs Navigation -->
        <nav class="flex text-xs font-semibold uppercase tracking-wider" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-text-disabled">
                <li class="inline-flex items-center">
                    <a href="{{ route('landing.index') }}" class="hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                        Beranda
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-text-disabled/60 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        <span class="text-text-main dark:text-navy-accent font-bold">Direktori Dosen & Peneliti</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header Card -->
        <div class="bg-white border border-border-main rounded-3xl p-6 sm:p-10 shadow-card dark:bg-dark-surface dark:border-dark-border relative overflow-hidden">
            <div class="max-w-2xl space-y-3 relative z-10">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-mahardika-navy-pale text-mahardika-navy text-[11px] font-bold dark:bg-navy-accent/20 dark:text-navy-accent">
                    <span>👥 Database Akademisi Terdaftar</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold font-primary text-text-main dark:text-white leading-tight">
                    Direktori Dosen & Peneliti Mahardika
                </h1>
                <p class="text-xs sm:text-sm text-text-secondary dark:text-dark-text-muted leading-relaxed font-body">
                    Eksplorasi profil lengkap akademisi, kepakaran riset, publikasi internasional, serta riwayat pengabdian dosen Universitas Insan Mahardika.
                </p>
            </div>

            <!-- Search and Filter Controls -->
            <div class="mt-8 pt-6 border-t border-border-main dark:border-dark-border flex flex-col sm:flex-row gap-4 items-stretch sm:items-center justify-between">
                <!-- Search input field -->
                <div class="relative flex-1 max-w-md">
                    <input type="text" 
                           x-model="search" 
                           placeholder="Cari nama dosen atau bidang keahlian..." 
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-border-main bg-bg-surface focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:bg-dark-surface dark:border-dark-border dark:focus:ring-navy-accent/25 text-xs sm:text-sm">
                    <svg class="w-5 h-5 text-text-disabled absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <!-- Selection dropdown filter -->
                <div class="relative">
                    <select x-model="facultyFilter" 
                            class="w-full sm:w-56 pl-3 pr-8 py-3 rounded-xl border border-border-main bg-bg-surface dark:bg-dark-surface dark:border-dark-border text-xs sm:text-sm focus:outline-none appearance-none cursor-pointer font-medium">
                        <option value="semua">Semua Fakultas</option>
                        <option value="Fakultas Ilmu Kesehatan">Ilmu Kesehatan</option>
                        <option value="Fakultas Ilmu Komputer">Ilmu Komputer</option>
                        <option value="Fakultas Farmasi">Farmasi</option>
                        <option value="Fakultas Teknik">Teknik</option>
                        <option value="Fakultas Keguruan & Ilmu Pendidikan">Keguruan & Ilmu Pendidikan</option>
                        <option value="Fakultas Ekonomi & Bisnis">Ekonomi & Bisnis</option>
                    </select>
                    <svg class="w-4 h-4 text-text-disabled absolute right-3 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        </div>

        <!-- Lecturers dynamic cards list container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($lecturers as $lec)
            @php
                $avatarUrl = !empty($lec['avatar']) && !\Illuminate\Support\Str::startsWith($lec['avatar'], ['http://', 'https://', '/']) 
                    ? asset('storage/' . $lec['avatar']) 
                    : ($lec['avatar'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=256');
            @endphp
            <div x-show="(search === '' || '{{ strtolower($lec['name']) }}'.includes(search.toLowerCase()) || '{{ strtolower($lec['expertise']) }}'.includes(search.toLowerCase())) && (facultyFilter === 'semua' || '{{ $lec['faculty'] }}' === facultyFilter)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="p-6 rounded-2xl bg-white border border-border-main shadow-subtle hover:shadow-card hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border flex flex-col justify-between group">
                
                <div class="space-y-4">
                    <!-- Lecturer header information -->
                    <div class="flex items-center space-x-4">
                        <div class="relative w-14 h-14 rounded-full overflow-hidden border-2 border-mahardika-navy/10 group-hover:border-mahardika-navy/30 transition-colors duration-200 dark:border-dark-border shrink-0">
                            <img src="{{ $avatarUrl }}" alt="{{ $lec['name'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col min-w-0">
                            <h4 class="font-primary font-bold text-sm text-text-main dark:text-white leading-tight group-hover:text-mahardika-navy dark:group-hover:text-navy-accent transition-colors duration-150 truncate">{{ $lec['name'] }}</h4>
                            <span class="text-[10px] text-text-secondary dark:text-dark-text-muted mt-0.5 font-semibold">NIDN: {{ $lec['nidn'] }}</span>
                        </div>
                    </div>

                    <div class="h-px bg-border-main dark:bg-dark-border"></div>

                    <!-- Details Area -->
                    <div class="space-y-2.5 text-xs">
                        <div class="flex flex-col space-y-0.5">
                            <span class="text-[9px] uppercase tracking-wider font-bold text-text-disabled">Fakultas / Program Studi</span>
                            <span class="text-text-main dark:text-gray-200 font-medium">{{ $lec['faculty'] }}</span>
                            <span class="text-text-secondary dark:text-dark-text-muted text-[11px]">{{ $lec['study_program'] }}</span>
                        </div>
                        <div class="flex flex-col space-y-0.5">
                            <span class="text-[9px] uppercase tracking-wider font-bold text-text-disabled">Bidang Keahlian Riset</span>
                            <span class="inline-block px-2.5 py-1 rounded-lg bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent text-[11px] font-semibold w-fit">
                                {{ $lec['expertise'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-border-main/50 dark:border-dark-border flex">
                    <a href="{{ route('landing.lecturer', ['slug' => $lec['slug']]) }}" class="w-full text-center py-2.5 rounded-xl text-xs font-semibold border border-border-main text-text-main hover:bg-mahardika-navy-pale dark:border-dark-border dark:text-gray-200 dark:hover:bg-dark-surface-soft hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150 shadow-subtle">
                        Lihat Profil Akademik &rarr;
                    </a>
                </div>

            </div>
            @empty
            <div class="col-span-full text-center py-16 bg-white border border-dashed border-border-main rounded-3xl dark:bg-dark-surface dark:border-dark-border">
                <svg class="w-12 h-12 text-text-disabled mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <h4 class="font-primary font-bold text-base text-text-main dark:text-white">Belum Ada Data Dosen</h4>
                <p class="font-body text-xs text-text-secondary mt-1 dark:text-dark-text-muted">Data dosen yang ditambahkan melalui panel admin akan langsung tampil di sini.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
