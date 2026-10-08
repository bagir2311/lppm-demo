@extends('layouts.app')

@section('title', 'Lembar Penilaian Proposal - Reviewer LPPM')

@section('content')
<div class="pt-28 pb-16 bg-bg-alt dark:bg-[#0b1220] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        @php
            $targetItem = $penilaian->penelitian ?? $penilaian->pengabdian;
        @endphp

        <!-- Header Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('reviewer.dashboard') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-mahardika-navy dark:text-blue-400 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Dashboard Reviewer</span>
            </a>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/40 text-mahardika-navy dark:text-blue-300">
                Lembar Penilaian BIMA
            </span>
        </div>

        <!-- Proposal Info Card -->
        <div class="bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-2xl p-6 sm:p-8 shadow-card space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-mahardika-navy dark:bg-blue-900/30 dark:text-blue-300 uppercase tracking-wider">
                    {{ $targetItem->skema ?? 'Penelitian' }}
                </span>
                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-mahardika-green dark:bg-green-900/30">
                    Bidang: {{ $targetItem->bidang_fokus ?? '-' }}
                </span>
            </div>

            <h2 class="font-primary font-extrabold text-xl text-text-main dark:text-white leading-snug">
                {{ $targetItem->judul ?? 'Judul Proposal Riset' }}
            </h2>

            <div class="flex items-center space-x-3 pt-2 border-t border-gray-100 dark:border-dark-border">
                <img src="{{ $targetItem->ketuaDosen->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=256' }}" 
                     alt="Dosen" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <span class="text-xs font-bold text-text-main dark:text-white block">{{ $targetItem->ketuaDosen->nama ?? 'Dosen Pengusul' }}</span>
                    <span class="text-[10px] text-text-sub dark:text-dark-text-muted">{{ $targetItem->ketuaDosen->prodi ?? '-' }} (NIDN: {{ $targetItem->ketuaDosen->nidn ?? '-' }})</span>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-dark-surface-soft p-4 rounded-xl text-xs text-text-sub dark:text-dark-text-muted leading-relaxed">
                <strong class="text-text-main dark:text-white">Abstrak Proposal:</strong><br>
                {{ $targetItem->abstrak ?? 'Abstrak proposal belum diunggah.' }}
            </div>
        </div>

        <!-- Scoring Form -->
        <div class="bg-white dark:bg-dark-surface border border-gray-200 dark:border-dark-border rounded-2xl p-6 sm:p-8 shadow-card space-y-6">
            <h3 class="font-primary font-bold text-base text-text-main dark:text-white border-b border-gray-100 dark:border-dark-border pb-3">
                Formulir Evaluasi & Penilaian
            </h3>

            <form action="{{ route('reviewer.penilaian.store', $penilaian->id) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Metodologi -->
                    <div class="bg-gray-50 dark:bg-dark-surface-soft p-4 rounded-xl border border-gray-100 dark:border-dark-border space-y-2">
                        <label class="block text-xs font-bold text-text-main dark:text-white">1. Kualitas Metodologi (40%)</label>
                        <p class="text-[10px] text-text-sub dark:text-dark-text-muted">Kerapian rancangan, sampel, dan alur penelitian (Skala 0-100).</p>
                        <input type="number" name="skor_metodologi" min="0" max="100" value="{{ old('skor_metodologi', $penilaian->skor_metodologi) }}" required
                               class="w-full px-3 py-2 border rounded-xl text-xs font-bold dark:bg-dark-surface dark:border-dark-border dark:text-white">
                    </div>

                    <!-- Relevansi -->
                    <div class="bg-gray-50 dark:bg-dark-surface-soft p-4 rounded-xl border border-gray-100 dark:border-dark-border space-y-2">
                        <label class="block text-xs font-bold text-text-main dark:text-white">2. Relevansi & Urgensi (30%)</label>
                        <p class="text-[10px] text-text-sub dark:text-dark-text-muted">Kesesuaian dengan rencana induk LPPM (Skala 0-100).</p>
                        <input type="number" name="skor_relevansi" min="0" max="100" value="{{ old('skor_relevansi', $penilaian->skor_relevansi) }}" required
                               class="w-full px-3 py-2 border rounded-xl text-xs font-bold dark:bg-dark-surface dark:border-dark-border dark:text-white">
                    </div>

                    <!-- Kelayakan Biaya -->
                    <div class="bg-gray-50 dark:bg-dark-surface-soft p-4 rounded-xl border border-gray-100 dark:border-dark-border space-y-2">
                        <label class="block text-xs font-bold text-text-main dark:text-white">3. Kelayakan Biaya & Output (30%)</label>
                        <p class="text-[10px] text-text-sub dark:text-dark-text-muted">Rasionalitas RAB dan target luaran publikasi (Skala 0-100).</p>
                        <input type="number" name="skor_kelayakan" min="0" max="100" value="{{ old('skor_kelayakan', $penilaian->skor_kelayakan) }}" required
                               class="w-full px-3 py-2 border rounded-xl text-xs font-bold dark:bg-dark-surface dark:border-dark-border dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Rekomendasi Akhir Reviewer *</label>
                    <select name="rekomendasi" required class="w-full px-3 py-2.5 border rounded-xl text-xs font-bold dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">
                        <option value="Diterima" {{ $penilaian->rekomendasi === 'Diterima' ? 'selected' : '' }}>Diterima (Disetujui Didanai)</option>
                        <option value="Revisi" {{ $penilaian->rekomendasi === 'Revisi' ? 'selected' : '' }}>Revisi Mayor / Minor</option>
                        <option value="Ditolak" {{ $penilaian->rekomendasi === 'Ditolak' ? 'selected' : '' }}>Ditolak (Tidak Layak Didanai)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Catatan Evaluasi & Masukan Reviewer</label>
                    <textarea name="catatan_reviewer" rows="4" placeholder="Tuliskan saran perbaikan metodologi atau catatan RAB untuk peneliti..."
                              class="w-full px-3 py-2 border rounded-xl text-xs dark:bg-dark-surface-soft dark:border-dark-border dark:text-white">{{ old('catatan_reviewer', $penilaian->catatan_reviewer) }}</textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-dark-border">
                    <a href="{{ route('reviewer.dashboard') }}" class="px-4 py-2 bg-gray-100 text-xs font-bold rounded-xl">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-mahardika-navy text-white text-xs font-bold rounded-xl shadow-md">Simpan Evaluasi Proposal</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
