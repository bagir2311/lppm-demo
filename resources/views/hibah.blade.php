@extends('layouts.app')

@section('title', 'Dashboard Manajemen Hibah - LPPM Universitas Insan Mahardika')

@section('content')
<div class="w-full min-h-screen bg-gray-50 dark:bg-gray-900 py-8 lg:py-12">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- 1. Header & Call to Action (CTA) --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#00295f] dark:text-blue-400">
                    Sistem Informasi Manajemen Riset &amp; Pengmas
                </span>
                <h1 class="font-primary font-extrabold text-2xl sm:text-3xl text-gray-900 dark:text-white mt-1">
                    Dashboard Manajemen Hibah
                </h1>
                <p class="font-body text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1.5 leading-relaxed max-w-2xl">
                    Kelola dan pantau seluruh siklus proposal hibah penelitian dan pengabdian masyarakat dosen sesuai standar BIMA Kemdiktisaintek.
                </p>
            </div>
            
            {{-- Tombol Utama CTA --}}
            <div class="shrink-0">
                <a href="{{ route('berkas.index') }}" 
                   class="inline-flex items-center space-x-2 bg-[#00295f] text-white hover:bg-blue-900 font-semibold px-5 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-150 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Ajukan Proposal Baru</span>
                </a>
            </div>
        </div>

        {{-- 2. Kartu Ringkasan (Summary Cards) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            {{-- Card 1: Total Pengajuan --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center space-x-4">
                <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-[#00295f] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pengajuan</span>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalPengajuan ?? 0 }}</h3>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400">Proposal terdaftar</span>
                </div>
            </div>

            {{-- Card 2: Menunggu Review --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center space-x-4">
                <div class="w-14 h-14 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Menunggu Review</span>
                    <h3 class="text-3xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $menungguReview ?? 0 }}</h3>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400">Tahap evaluasi penilai</span>
                </div>
            </div>

            {{-- Card 3: Disetujui / Didanai --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center space-x-4">
                <div class="w-14 h-14 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Disetujui / Didanai</span>
                    <h3 class="text-3xl font-extrabold text-green-700 dark:text-green-400 mt-1">{{ $disetujui ?? 0 }}</h3>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400">Lolos seleksi pendanaan</span>
                </div>
            </div>
        </div>

        {{-- 3. Tabel Riwayat Pengajuan Proposal --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-primary font-bold text-lg text-gray-900 dark:text-white">
                        Daftar Riwayat Usulan Hibah
                    </h2>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">
                        Daftar usulan penelitian dan pengabdian yang tercatat di portal LPPM.
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('katalog') }}" class="text-xs font-semibold text-[#00295f] hover:underline dark:text-blue-400">
                        Lihat Katalog Publik →
                    </a>
                </div>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 font-semibold dark:bg-gray-800 dark:text-gray-200 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Judul Proposal</th>
                            <th class="py-3.5 px-4">Skema</th>
                            <th class="py-3.5 px-4">Tanggal Pengajuan</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-xs sm:text-sm">
                        @forelse($proposals as $index => $item)
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-4 px-4 text-center font-medium text-gray-600 dark:text-gray-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-gray-900 dark:text-white line-clamp-1">
                                    {{ $item->judul }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 block">
                                    Ketua: {{ $item->ketuaDosen->nama ?? 'Dosen Peneliti' }} 
                                    @if($item->ketuaDosen && $item->ketuaDosen->nidn)
                                        (NIDN: {{ $item->ketuaDosen->nidn }})
                                    @endif
                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-[#00295f] dark:bg-gray-700 dark:text-blue-300">
                                    {{ $item->skema ?? 'Penelitian Dasar' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-gray-600 dark:text-gray-400">
                                {{ $item->created_at ? $item->created_at->format('d M Y') : 'Tahun ' . $item->tahun_pelaksanaan }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @php
                                    $st = strtolower($item->status ?? 'diajukan');
                                @endphp
                                @if(in_array($st, ['didanai', 'disetujui', 'selesai']))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600 dark:bg-green-400 mr-1.5"></span>
                                        {{ $item->status ?? 'Didanai' }}
                                    </span>
                                @elseif(in_array($st, ['review', 'pending']))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 dark:bg-amber-400 mr-1.5"></span>
                                        {{ $item->status ?? 'Menunggu Review' }}
                                    </span>
                                @elseif(in_array($st, ['ditolak', 'revisi']))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600 dark:bg-red-400 mr-1.5"></span>
                                        {{ $item->status ?? 'Revisi' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400 mr-1.5"></span>
                                        {{ $item->status ?? 'Diajukan' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('catalog.penelitian.show', $item->id) }}" 
                                   class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-[#00295f] hover:text-white dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-[#00295f] transition-all">
                                    <span>Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z" />
                                </svg>
                                Belum ada usulan proposal hibah yang diajukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer info --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-800/60 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 flex items-center justify-between">
                <span>Menampilkan seluruh usulan proposal hibah aktif</span>
                <span>LPPM Universitas Insan Mahardika</span>
            </div>
        </div>

    </div>
</div>
@endsection
