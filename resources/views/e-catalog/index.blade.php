@extends('layouts.app')

@section('title', 'E-Catalog Riset & Pengabdian - LPPM Universitas Insan Mahardika')

@section('content')
<div class="w-full min-h-screen bg-bg-alt dark:bg-bg-main/60 pt-28 pb-20"
     x-data="{
         activeType: '{{ $type }}',
         search: '{{ $search ?? '' }}',
         get filtered() {
             return this.activeType;
         }
     }">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- =============================================
             BREADCRUMBS (identik dengan halaman Portal)
             ============================================= --}}
        <nav class="flex mb-8 text-xs font-semibold uppercase tracking-wider" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-text-disabled">
                <li class="inline-flex items-center">
                    <a href="{{ route('landing.index') }}"
                       class="hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        Beranda
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-text-disabled/60 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-main dark:text-navy-accent font-bold">E-Catalog Riset</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- =============================================
             BANNER HEADER — gaya card navy (DESIGN_MAHARDIKA)
             ============================================= --}}
        <div class="bg-gradient-to-br from-mahardika-navy to-[#1a3d6d] text-white p-8 md:p-12 rounded-3xl shadow-floating dark:from-dark-surface dark:to-dark-surface-soft border border-white/10 dark:border-dark-border mb-10 relative overflow-hidden group">
            {{-- Decorative watermark --}}
            <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none group-hover:scale-105 transition-transform duration-500">
                <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded bg-white/10 text-blue-200 border border-white/20 font-primary font-bold text-[10px] uppercase tracking-wider">
                        <span>LPPM Universitas Insan Mahardika</span>
                    </div>
                    <h1 class="font-primary font-extrabold text-2xl sm:text-3xl lg:text-4xl text-white leading-tight">
                        E-Catalog Riset &amp; Pengabdian
                    </h1>
                    <p class="font-body text-sm text-blue-200/85 max-w-2xl leading-relaxed">
                        Direktori terpadu hasil penelitian dan pengabdian masyarakat civitas akademika.<br>
                        Temukan karya ilmiah, hibah, dan inovasi dari dosen &amp; peneliti Mahardika.
                    </p>
                </div>
                {{-- Stats Counter --}}
                <div class="flex items-center gap-6 shrink-0">
                    <div class="text-center">
                        <p class="font-primary font-extrabold text-3xl text-white">{{ $totalPenelitianCount }}</p>
                        <p class="text-[11px] uppercase tracking-wider text-blue-200/70 font-semibold mt-0.5">Penelitian</p>
                    </div>
                    <div class="w-px h-10 bg-white/20"></div>
                    <div class="text-center">
                        <p class="font-primary font-extrabold text-3xl text-white">{{ $totalPengabdianCount }}</p>
                        <p class="text-[11px] uppercase tracking-wider text-blue-200/70 font-semibold mt-0.5">Pengabdian</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================
             FORM FILTER — card putih tepat di bawah banner
             ============================================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <form method="GET" action="{{ route('catalog.index') }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">

                    {{-- Search Input --}}
                    <div class="sm:col-span-2 lg:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            Cari Katalog
                        </label>
                        <div class="relative">
                            <input type="text" name="search"
                                   placeholder="Judul, abstrak, nama dosen..."
                                   value="{{ $search ?? '' }}"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 focus:outline-none focus:ring-2 text-sm placeholder-gray-400 dark:placeholder-gray-500">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            Tahun
                        </label>
                        <select name="tahun"
                                class="w-full py-2.5 px-3 rounded-xl border bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:outline-none focus:ring-2 text-sm cursor-pointer shadow-xs">
                            <option value="" class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800">Semua Tahun</option>
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Skema --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            Skema
                        </label>
                        <select name="skema"
                                class="w-full py-2.5 px-3 rounded-xl border bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:outline-none focus:ring-2 text-sm cursor-pointer shadow-xs">
                            <option value="" class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800">Semua Skema</option>
                            @foreach($availableSkemas as $s)
                                <option value="{{ $s }}" class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" {{ $skema == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Submit --}}
                    <div>
                        <button type="submit"
                                class="w-full py-2.5 px-4 rounded-xl font-primary font-bold text-xs bg-[#00295f] text-white hover:bg-[#001c4a] shadow-subtle flex items-center justify-center space-x-1.5 transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- =============================================
             TAB FILTER — identik gaya portal
             ============================================= --}}
        <div class="flex flex-wrap items-center gap-2 mb-10 pb-2 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('catalog.index', array_merge(request()->except('type'), ['type' => 'all'])) }}"
               class="px-4 py-3 text-xs uppercase tracking-wider transition-all duration-150
                      {{ ($type === 'all' || !$type) ? 'text-[#00295f] font-bold border-b-2 border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-[#00295f] dark:hover:text-white' }}">
                Semua Karya
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('type'), ['type' => 'penelitian'])) }}"
               class="px-4 py-3 text-xs uppercase tracking-wider transition-all duration-150
                      {{ $type === 'penelitian' ? 'text-[#00295f] font-bold border-b-2 border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-[#00295f] dark:hover:text-white' }}">
                Penelitian
            </a>
            <a href="{{ route('catalog.index', array_merge(request()->except('type'), ['type' => 'pengabdian'])) }}"
               class="px-4 py-3 text-xs uppercase tracking-wider transition-all duration-150
                      {{ $type === 'pengabdian' ? 'text-[#00295f] font-bold border-b-2 border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-[#00295f] dark:hover:text-white' }}">
                Pengabdian Masyarakat
            </a>
        </div>

        {{-- =============================================
             SECTION HEADER — judul seksi seperti portal
             ============================================= --}}
        <div class="mb-8">
            <span class="font-primary font-bold text-xs uppercase tracking-widest text-[#00295f] dark:text-blue-400">
                Direktori Riset
            </span>
            <h2 class="font-primary font-extrabold text-3xl text-gray-900 dark:text-white mt-2 leading-none">
                {{ ($type === 'penelitian') ? 'Penelitian' : (($type === 'pengabdian') ? 'Pengabdian Masyarakat' : 'Semua Karya Ilmiah') }}
            </h2>
            <div class="h-1.5 w-16 bg-mahardika-green mt-4 rounded-full"></div>
        </div>

        {{-- =============================================
             PENELITIAN GRID
             ============================================= --}}
        @if($type === 'all' || $type === 'penelitian')
            @if($penelitians->isNotEmpty())
                @if($type === 'all')
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-primary font-bold text-sm uppercase tracking-widest text-gray-700 dark:text-gray-300">
                        Penelitian
                    </h3>
                    <a href="{{ route('catalog.index', array_merge(request()->except('type'), ['type' => 'penelitian'])) }}"
                       class="text-xs text-[#00295f] font-semibold hover:underline dark:text-blue-400">
                        Lihat Semua →
                    </a>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($penelitians as $item)
                    <article class="p-6 rounded-3xl bg-white border border-gray-200 shadow-card hover:shadow-floating hover:-translate-y-1 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 flex flex-col justify-between group">
                        <div class="space-y-4">
                            {{-- Top Row: Type badge & Status --}}
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-gray-700 flex items-center justify-center text-[#00295f] dark:text-blue-400 shadow-subtle transition-transform duration-300 group-hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium uppercase tracking-wider bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600 dark:bg-green-400 mr-1.5 animate-pulse"></span>
                                    {{ $item->status ?? 'Didanai' }}
                                </span>
                            </div>

                            {{-- Title & Meta --}}
                            <div class="space-y-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                                    Penelitian · {{ $item->tahun_pelaksanaan }}
                                </span>
                                <h3 class="font-primary text-gray-900 font-bold dark:text-white group-hover:text-[#00295f] dark:group-hover:text-blue-400 transition-colors duration-150 line-clamp-2 leading-snug text-base">
                                    {{ $item->judul }}
                                </h3>
                                <p class="font-body text-xs text-gray-700 dark:text-gray-300 leading-relaxed line-clamp-2">
                                    {{ $item->abstrak ?? 'Abstrak belum tersedia.' }}
                                </p>
                            </div>

                            {{-- Tags --}}
                            <div class="flex flex-wrap gap-1.5">
                                @if($item->skema)
                                    <span class="px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wide bg-blue-50 text-[#00295f] dark:bg-gray-700 dark:text-blue-300 rounded-md">
                                        {{ $item->skema }}
                                    </span>
                                @endif
                                @if($item->bidang_fokus)
                                    <span class="px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wide bg-sky-50 text-sky-800 dark:bg-gray-700 dark:text-sky-300 rounded-md">
                                        {{ $item->bidang_fokus }}
                                    </span>
                                @endif
                            </div>

                            {{-- Dosen Info --}}
                            @if($item->ketuaDosen)
                            <div class="flex items-center space-x-2.5 pt-1">
                                <img src="{{ $item->ketuaDosen->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($item->ketuaDosen->nama).'&size=32&background=00295f&color=fff' }}"
                                     alt="{{ $item->ketuaDosen->nama }}"
                                     class="w-7 h-7 rounded-lg object-cover border border-gray-300 dark:border-gray-600">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold text-gray-700 dark:text-gray-300 truncate leading-none">{{ $item->ketuaDosen->nama }}</p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate leading-none mt-0.5">{{ $item->ketuaDosen->prodi ?? '' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- CTA --}}
                        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('catalog.penelitian.show', $item->id) }}"
                               class="w-full py-2.5 rounded-xl font-primary font-bold text-xs bg-gray-50 text-[#00295f] border border-gray-200 hover:bg-[#00295f] hover:text-white hover:border-[#00295f] text-center flex items-center justify-center space-x-1.5 transition-all duration-150 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-[#00295f]">
                                <span>Lihat Detail Riset</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- =============================================
             PENGABDIAN GRID
             ============================================= --}}
        @if($type === 'all' || $type === 'pengabdian')
            @if($pengabdians->isNotEmpty())
                @if($type === 'all')
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-primary font-bold text-sm uppercase tracking-widest text-gray-700 dark:text-gray-300">
                        Pengabdian Masyarakat
                    </h3>
                    <a href="{{ route('catalog.index', array_merge(request()->except('type'), ['type' => 'pengabdian'])) }}"
                       class="text-xs text-[#00295f] font-semibold hover:underline dark:text-blue-400">
                        Lihat Semua →
                    </a>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($pengabdians as $item)
                    <article class="p-6 rounded-3xl bg-white border border-gray-200 shadow-card hover:shadow-floating hover:-translate-y-1 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 flex flex-col justify-between group">
                        <div class="space-y-4">
                            {{-- Top Row: Icon & Status --}}
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-gray-700 flex items-center justify-center text-green-700 dark:text-green-400 shadow-subtle transition-transform duration-300 group-hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium uppercase tracking-wider bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600 dark:bg-green-400 mr-1.5 animate-pulse"></span>
                                    {{ $item->status ?? 'Didanai' }}
                                </span>
                            </div>

                            {{-- Title & Meta --}}
                            <div class="space-y-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                                    Pengabdian · {{ $item->tahun_pelaksanaan }}
                                </span>
                                <h3 class="font-primary text-gray-900 font-bold dark:text-white group-hover:text-[#00295f] dark:group-hover:text-blue-400 transition-colors duration-150 line-clamp-2 leading-snug text-base">
                                    {{ $item->judul }}
                                </h3>
                                <p class="font-body text-xs text-gray-700 dark:text-gray-300 leading-relaxed line-clamp-2">
                                    {{ $item->abstrak ?? 'Abstrak belum tersedia.' }}
                                </p>
                            </div>

                            {{-- Tags --}}
                            <div class="flex flex-wrap gap-1.5">
                                @if($item->skema)
                                    <span class="px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wide bg-green-50 text-green-800 dark:bg-gray-700 dark:text-green-300 rounded-md">
                                        {{ $item->skema }}
                                    </span>
                                @endif
                                @if($item->bidang_fokus)
                                    <span class="px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wide bg-sky-50 text-sky-800 dark:bg-gray-700 dark:text-sky-300 rounded-md">
                                        {{ $item->bidang_fokus }}
                                    </span>
                                @endif
                                @if(isset($item->lokasi_mitra) && $item->lokasi_mitra)
                                    <span class="px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wide bg-amber-50 text-amber-800 dark:bg-gray-700 dark:text-amber-300 rounded-md">
                                        {{ $item->lokasi_mitra }}
                                    </span>
                                @endif
                            </div>

                            {{-- Dosen Info --}}
                            @if($item->ketuaDosen)
                            <div class="flex items-center space-x-2.5 pt-1">
                                <img src="{{ $item->ketuaDosen->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($item->ketuaDosen->nama).'&size=32&background=00295f&color=fff' }}"
                                     alt="{{ $item->ketuaDosen->nama }}"
                                     class="w-7 h-7 rounded-lg object-cover border border-gray-300 dark:border-gray-600">
                                <div class="min-w-0">
                                    <p class="text-[11px] font-bold text-gray-700 dark:text-gray-300 truncate leading-none">{{ $item->ketuaDosen->nama }}</p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate leading-none mt-0.5">{{ $item->ketuaDosen->prodi ?? '' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- CTA --}}
                        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('catalog.pengabdian.show', $item->id) }}"
                               class="w-full py-2.5 rounded-xl font-primary font-bold text-xs bg-gray-50 text-[#00295f] border border-gray-200 hover:bg-[#00295f] hover:text-white hover:border-[#00295f] text-center flex items-center justify-center space-x-1.5 transition-all duration-150 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-[#00295f]">
                                <span>Lihat Detail Pengabdian</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- =============================================
             EMPTY STATE — identik dengan portal
             ============================================= --}}
        @if($penelitians->isEmpty() && $pengabdians->isEmpty())
        <div class="text-center py-20 bg-white border border-dashed border-border-main rounded-3xl dark:bg-dark-surface dark:border-dark-border">
            <svg class="w-12 h-12 text-text-disabled mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h4 class="font-primary font-bold text-base text-text-main dark:text-white">Data tidak ditemukan</h4>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted mt-1">
                Coba gunakan kata kunci atau filter yang berbeda.
            </p>
            <a href="{{ route('catalog.index') }}"
               class="inline-flex items-center mt-4 px-5 py-2 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-[#001c4a] transition-all">
                Reset Filter
            </a>
        </div>
        @endif

    </div>
</div>
@endsection
