@extends('layouts.app')

@section('title', $item->judul . ' - E-Catalog LPPM Mahardika')

@section('content')
<!-- Header Header Detail -->
<section class="pt-28 pb-12 bg-mahardika-navy text-white relative">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-xs text-blue-200">
            <a href="{{ route('landing.index') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog.index') }}" class="hover:underline">E-Catalog</a>
            <span>/</span>
            <span class="text-white font-semibold line-clamp-1">{{ $typeLabel }}</span>
        </nav>

        <div class="flex flex-wrap items-center gap-2 pt-2">
            <span class="px-3 py-1 rounded-md text-xs font-bold bg-white/10 border border-white/20 text-blue-200 uppercase tracking-wider">
                {{ $item->skema }}
            </span>
            <span class="px-3 py-1 rounded-md text-xs font-bold bg-mahardika-green text-white">
                Status: {{ $item->status }} ({{ $item->tahun_pelaksanaan }})
            </span>
            <span class="px-3 py-1 rounded-md text-xs font-semibold bg-blue-900/60 text-blue-300">
                Bidang: {{ $item->bidang_fokus }}
            </span>
        </div>

        <h1 class="font-primary font-extrabold text-2xl sm:text-3xl lg:text-4xl leading-snug">
            {{ $item->judul }}
        </h1>
    </div>
</section>

<!-- Detail Content Section -->
<section class="py-12 bg-bg-alt dark:bg-[#0b1220] min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Main Column (Abstrak & Metadata) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Abstract Card -->
                <div class="bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-2xl p-6 sm:p-8 shadow-card space-y-4">
                    <h3 class="font-primary font-bold text-base text-text-main dark:text-white border-b border-gray-100 dark:border-dark-border pb-3 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-mahardika-navy dark:text-navy-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/></svg>
                        <span>Abstrak / Ringkasan Riset</span>
                    </h3>

                    <p class="text-sm text-text-sub dark:text-dark-text-muted leading-relaxed whitespace-pre-line">
                        {{ $item->abstrak ?? 'Abstrak belum diunggah untuk katalog riset ini.' }}
                    </p>

                    @if($item->file_pdf_url)
                    <div class="pt-4 border-t border-gray-100 dark:border-dark-border flex items-center justify-between">
                        <span class="text-xs font-semibold text-text-sub dark:text-dark-text-muted">Dokumen Laporan / Publikasi PDF:</span>
                        <a href="{{ $item->file_pdf_url }}" target="_blank" 
                           class="inline-flex items-center space-x-2 px-4 py-2 bg-mahardika-navy hover:bg-blue-900 text-white rounded-xl text-xs font-bold transition-all shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh Dokumen Riset</span>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Metadata Grid -->
                <div class="bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-2xl p-6 shadow-card grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-[11px] text-text-sub dark:text-dark-text-muted uppercase tracking-wider font-semibold">Tahun Pelaksanaan</span>
                        <p class="text-sm font-bold text-text-main dark:text-white mt-1">{{ $item->tahun_pelaksanaan }}</p>
                    </div>
                    <div>
                        <span class="text-[11px] text-text-sub dark:text-dark-text-muted uppercase tracking-wider font-semibold">Skema BIMA</span>
                        <p class="text-sm font-bold text-text-main dark:text-white mt-1">{{ $item->skema }}</p>
                    </div>
                    @if($item->dana_disetujui)
                    <div>
                        <span class="text-[11px] text-text-sub dark:text-dark-text-muted uppercase tracking-wider font-semibold">Dana Hibah Disetujui</span>
                        <p class="text-sm font-bold text-mahardika-green mt-1">Rp {{ number_format($item->dana_disetujui, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    @if(isset($item->lokasi_mitra) && $item->lokasi_mitra)
                    <div>
                        <span class="text-[11px] text-text-sub dark:text-dark-text-muted uppercase tracking-wider font-semibold">Lokasi Mitra Pengmas</span>
                        <p class="text-sm font-bold text-text-main dark:text-white mt-1">{{ $item->lokasi_mitra }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column (Lecturer Info & Actions) -->
            <div class="space-y-6">
                <!-- Lecturer Card -->
                <div class="bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-2xl p-6 shadow-card space-y-4">
                    <h3 class="font-primary font-bold text-xs uppercase tracking-wider text-text-sub dark:text-dark-text-muted border-b border-gray-100 dark:border-dark-border pb-2">
                        Ketua Tim Pelaksana
                    </h3>

                    <div class="flex items-center space-x-4">
                        <img src="{{ $item->ketuaDosen->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=256' }}" 
                             alt="{{ $item->ketuaDosen->nama ?? 'Dosen' }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-mahardika-navy/20">
                        <div class="flex flex-col">
                            <h4 class="font-primary font-bold text-sm text-text-main dark:text-white">{{ $item->ketuaDosen->nama ?? 'Dosen Peneliti' }}</h4>
                            <span class="text-xs text-text-sub dark:text-dark-text-muted">NIDN: {{ $item->ketuaDosen->nidn ?? '-' }}</span>
                            <span class="text-xs font-semibold text-mahardika-navy dark:text-blue-400 mt-1">{{ $item->ketuaDosen->prodi ?? '-' }}</span>
                        </div>
                    </div>

                    @if($item->ketuaDosen->slug)
                    <a href="{{ route('landing.lecturer', $item->ketuaDosen->slug) }}" 
                       class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 rounded-xl border border-mahardika-navy/30 hover:bg-mahardika-navy hover:text-white text-mahardika-navy dark:text-blue-400 text-xs font-bold transition-all">
                        <span>Lihat Profil Lengkap Dosen</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    @endif
                </div>

                <!-- Back button -->
                <a href="{{ route('catalog.index') }}" class="w-full flex items-center justify-center space-x-2 py-3 px-4 rounded-xl bg-gray-100 dark:bg-dark-surface-soft hover:bg-gray-200 text-text-main dark:text-white text-xs font-bold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Katalog Riset</span>
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
