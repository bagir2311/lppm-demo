@extends('layouts.app')

@section('title', 'Dashboard Reviewer Proposal LPPM Mahardika')

@section('content')
<div class="pt-28 pb-16 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Page -->
        <div class="bg-gradient-to-r from-[#00295f] to-[#001d45] text-white p-6 sm:p-8 rounded-2xl shadow-sm space-y-2">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold uppercase tracking-wider">
                <span>⭐ Portal Reviewer Independen</span>
            </div>
            <h1 class="font-primary font-bold text-2xl sm:text-3xl">Dashboard Evaluasi Proposal Hibah LPPM</h1>
            <p class="text-xs text-blue-200">Panel penugasan reviewer independen untuk penilaian substansi metodologi, relevansi, dan kelayakan anggaran proposal.</p>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/40 dark:border-green-800 dark:text-green-200 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button type="button" @click="$el.parentElement.remove()" class="text-green-700 hover:text-green-900 dark:text-green-300 cursor-pointer">✕</button>
        </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Total Penugasan Proposal</span>
                    <h3 class="font-primary font-bold text-2xl sm:text-3xl text-gray-900 dark:text-white mt-1">{{ $totalPenilaian }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center text-[#00295f] dark:text-blue-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Menunggu Evaluasi</span>
                    <h3 class="font-primary font-bold text-2xl sm:text-3xl text-red-600 dark:text-red-400 mt-1">{{ $pendingPenilaian }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/40 flex items-center justify-center text-red-600 dark:text-red-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Evaluasi Selesai</span>
                    <h3 class="font-primary font-bold text-2xl sm:text-3xl text-green-700 dark:text-green-400 mt-1">{{ $completedPenilaian }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/40 flex items-center justify-center text-green-700 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
        </div>

        <!-- Assigned Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Daftar Proposal Yang Ditugaskan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold uppercase text-xs border-b border-gray-200 dark:border-gray-700">
                            <th class="py-4 px-6">Judul Proposal</th>
                            <th class="py-4 px-6">Pengusul Utama</th>
                            <th class="py-4 px-6">Skema BIMA</th>
                            <th class="py-4 px-6">Total Skor</th>
                            <th class="py-4 px-6">Status Review</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        @forelse($penilaians as $item)
                        @php
                            $targetItem = $item->penelitian ?? $item->pengabdian;
                        @endphp
                        <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-50/70 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white max-w-xs">
                                <div class="line-clamp-2">{{ $targetItem->judul ?? 'Proposal Riset' }}</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                {{ $targetItem->ketuaDosen->nama ?? 'Dosen Pengusul' }}
                                <div class="text-[10px] text-gray-700 dark:text-gray-300 font-normal">{{ $targetItem->ketuaDosen->prodi ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-[#00295f] dark:bg-blue-900/40 dark:text-blue-300">
                                    {{ $targetItem->skema ?? 'Penelitian' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-primary font-bold text-sm {{ $item->total_skor >= 70 ? 'text-green-700 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $item->total_skor ?? 0 }} / 100
                                </div>
                                <div class="text-[10px] text-gray-700 dark:text-gray-300">Rekomendasi: {{ $item->rekomendasi ?? 'Belum ada' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($item->status === 'Selesai')
                                    <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Evaluasi Selesai</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('reviewer.penilaian', $item->id) }}" 
                                   class="px-4 py-2 bg-[#00295f] hover:bg-blue-900 text-white rounded-xl text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer inline-flex items-center space-x-1 shadow-xs">
                                    <span>{{ $item->status === 'Selesai' ? 'Edit Evaluasi' : 'Mulai Penilaian' }}</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-700 dark:text-gray-300">Belum ada penugasan proposal untuk Anda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
