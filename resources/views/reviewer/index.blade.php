@extends('layouts.app')

@section('title', 'Penugasan & Penilaian Proposal - Reviewer LPPM Mahardika')

@section('content')
<div class="pt-28 pb-16 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{
    modalOpen: false,
    selectedReview: null,
    editNilai: '',
    editCatatan: '',
    editStatus: 'pending',
    openEvaluationModal(item) {
        this.selectedReview = item;
        this.editNilai = item.nilai !== null ? item.nilai : '';
        this.editCatatan = item.catatan_revisi || '';
        this.editStatus = item.status_penilaian || 'pending';
        this.modalOpen = true;
    },
    closeModal() {
        this.modalOpen = false;
        this.selectedReview = null;
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Page Banner -->
        <div class="bg-gradient-to-r from-[#00295f] to-[#001d45] text-white p-6 sm:p-8 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="relative z-10 space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold uppercase tracking-wider">
                    <span>⭐ Portal Evaluasi Reviewer Independen</span>
                </div>
                <h1 class="font-primary font-bold text-2xl sm:text-3xl">Penugasan & Penilaian Proposal Hibah</h1>
                <p class="text-xs sm:text-sm text-blue-200 max-w-2xl">
                    Daftar proposal riset & pengabdian masyarakat yang secara khusus ditugaskan kepada Anda selaku Reviewer resmi LPPM Universitas Insan Mahardika.
                </p>
                @if(isset($reviewer) && $reviewer)
                <div class="pt-2 flex items-center space-x-3 text-xs text-blue-100">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-white/15 font-semibold">
                        👤 Reviewer: {{ $reviewer->nama }} {{ $reviewer->gelar ? ', ' . $reviewer->gelar : '' }}
                    </span>
                    <span class="hidden sm:inline-block text-blue-300">•</span>
                    <span class="hidden sm:inline-block">NIDN: {{ $reviewer->nidn ?? '-' }}</span>
                    <span class="hidden sm:inline-block text-blue-300">•</span>
                    <span class="hidden sm:inline-block">{{ $reviewer->prodi ?? 'Fakultas Teknologi' }}</span>
                </div>
                @endif
            </div>
            
            <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
                <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z"/>
                </svg>
            </div>
        </div>

        <!-- Feedback Alert Messages -->
        @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/40 dark:border-green-800 dark:text-green-200 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-green-700 hover:text-green-900 dark:text-green-300 cursor-pointer">✕</button>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/40 dark:border-red-800 dark:text-red-200 text-xs font-semibold space-y-1">
            <div class="font-bold">Terjadi kesalahan validasi:</div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 sm:gap-6">
            <!-- Total -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Ditugaskan</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="font-primary font-bold text-2xl text-gray-900 dark:text-white">{{ $totalPenugasan }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-[#00295f] dark:bg-blue-900/40 dark:text-blue-300 font-semibold">Semua</span>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">Menunggu (Pending)</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="font-primary font-bold text-2xl text-amber-600 dark:text-amber-400">{{ $pendingCount }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 font-semibold">Antrean</span>
                </div>
            </div>

            <!-- Revisi -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-orange-600 dark:text-orange-400">Perlu Revisi</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="font-primary font-bold text-2xl text-orange-600 dark:text-orange-400">{{ $revisiCount }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300 font-semibold">Revisi</span>
                </div>
            </div>

            <!-- Diterima -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-green-700 dark:text-green-400">Diterima / Lolos</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="font-primary font-bold text-2xl text-green-700 dark:text-green-400">{{ $diterimaCount }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-800 dark:bg-green-900/40 dark:text-green-300 font-semibold">Lolos</span>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="col-span-2 sm:col-span-1 bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-red-600 dark:text-red-400">Ditolak</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="font-primary font-bold text-2xl text-red-600 dark:text-red-400">{{ $ditolakCount }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-red-700 dark:bg-red-900/40 dark:text-red-300 font-semibold">Tolak</span>
                </div>
            </div>
        </div>

        <!-- Assigned Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-primary font-bold text-base sm:text-lg text-gray-900 dark:text-white">Daftar Proposal Yang Ditugaskan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Proposal yang harus ditinjau dan diberikan skor serta rekomendasi status penilaian.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs px-3 py-1 rounded-full bg-blue-50 text-[#00295f] dark:bg-blue-900/40 dark:text-blue-300 font-semibold">
                        {{ $penugasanReviews->count() }} Proposal Aktif
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700/60 text-gray-800 dark:text-gray-200 font-semibold uppercase text-[11px] border-b border-gray-200 dark:border-gray-700 tracking-wider">
                            <th class="py-4 px-6">Informasi Proposal</th>
                            <th class="py-4 px-6">Pengusul Utama (Dosen)</th>
                            <th class="py-4 px-6">Skema & Dana</th>
                            <th class="py-4 px-6 text-center">Nilai</th>
                            <th class="py-4 px-6 text-center">Status Penilaian</th>
                            <th class="py-4 px-6 text-right">Aksi Penilaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        @forelse($penugasanReviews as $review)
                        @php
                            $prop = $review->proposal;
                            $author = $prop ? $prop->dosen : null;
                        @endphp
                        <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-50/70 dark:hover:bg-gray-700/50 transition-colors">
                            <!-- Judul Proposal -->
                            <td class="py-4 px-6 max-w-sm">
                                <div class="font-bold text-gray-900 dark:text-white line-clamp-2">
                                    {{ $prop->judul ?? 'Proposal #' . $review->proposal_id }}
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">
                                    {{ $prop->bidang_fokus ?? 'Bidang Fokus Penelitian & Pengabdian LPPM' }}
                                </div>
                                @if($review->catatan_revisi)
                                <div class="mt-1 text-[11px] text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-750 p-1.5 rounded border border-gray-200 dark:border-gray-700 line-clamp-2">
                                    <span class="font-semibold text-gray-700 dark:text-gray-200">Catatan:</span> {{ $review->catatan_revisi }}
                                </div>
                                @endif
                            </td>

                            <!-- Pengusul Utama -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900 dark:text-white">
                                    {{ $author->nama ?? ($prop->author_name ?? 'Dosen Pengusul') }}
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                    NIDN: {{ $author->nidn ?? '-' }}
                                </div>
                                <div class="text-[10px] text-gray-400">
                                    {{ $author->prodi ?? 'Universitas Insan Mahardika' }}
                                </div>
                            </td>

                            <!-- Skema & Anggaran -->
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-[#00295f] dark:bg-blue-900/40 dark:text-blue-300 inline-block mb-1">
                                    {{ $prop->skema ?? 'Penelitian Dasar' }}
                                </span>
                                <div class="text-[11px] font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $prop ? 'Rp ' . number_format($prop->dana_usulan ?? 0, 0, ',', '.') : '-' }}
                                </div>
                                <div class="text-[10px] text-gray-400">
                                    Tahun: {{ $prop->tahun_anggaran ?? date('Y') }}
                                </div>
                            </td>

                            <!-- Nilai -->
                            <td class="py-4 px-6 text-center">
                                @if($review->nilai !== null)
                                    <div class="font-primary font-bold text-base {{ $review->nilai >= 70 ? 'text-green-700 dark:text-green-400' : ($review->nilai >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ number_format($review->nilai, 1) }}
                                    </div>
                                    <span class="text-[10px] text-gray-400">/ 100</span>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">Belum dinilai</span>
                                @endif
                            </td>

                            <!-- Status Penilaian -->
                            <td class="py-4 px-6 text-center">
                                @if($review->status_penilaian === 'diterima')
                                    <span class="bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 font-semibold px-2.5 py-1 rounded-full text-[11px] inline-flex items-center space-x-1">
                                        <span>●</span> <span>Diterima</span>
                                    </span>
                                @elseif($review->status_penilaian === 'revisi')
                                    <span class="bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200 font-semibold px-2.5 py-1 rounded-full text-[11px] inline-flex items-center space-x-1">
                                        <span>●</span> <span>Revisi</span>
                                    </span>
                                @elseif($review->status_penilaian === 'ditolak')
                                    <span class="bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 font-semibold px-2.5 py-1 rounded-full text-[11px] inline-flex items-center space-x-1">
                                        <span>●</span> <span>Ditolak</span>
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200 font-semibold px-2.5 py-1 rounded-full text-[11px] inline-flex items-center space-x-1">
                                        <span>●</span> <span>Pending</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-right">
                                <button type="button" 
                                        @click="openEvaluationModal({{ json_encode($review) }})"
                                        class="px-3.5 py-2 bg-[#00295f] hover:bg-blue-900 text-white rounded-xl text-xs font-semibold hover:opacity-95 transition-opacity cursor-pointer inline-flex items-center space-x-1.5 shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>{{ $review->nilai !== null ? 'Perbarui Nilai' : 'Beri Penilaian' }}</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-semibold text-gray-700 dark:text-gray-300">Belum Ada Penugasan Proposal</p>
                                    <p class="text-xs text-gray-400">Saat ini tidak ada proposal yang sedang ditugaskan kepada akun reviewer Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Form Penilaian & Catatan Revisi -->
    <div x-show="modalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4"
         @keydown.escape.window="closeModal()">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-gray-200 dark:border-gray-700"
             @click.away="closeModal()">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#00295f] dark:text-blue-400 bg-blue-50 dark:bg-blue-900/40 px-2 py-0.5 rounded">Form Penilaian Proposal</span>
                    <h3 class="font-primary font-bold text-lg text-gray-900 dark:text-white" x-text="selectedReview?.proposal?.judul || 'Form Penilaian Reviewer'"></h3>
                </div>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl font-bold cursor-pointer">✕</button>
            </div>

            <!-- Form Penilaian -->
            <form :action="'{{ url('/reviewer/penugasan') }}/' + (selectedReview?.id || '')" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <!-- Nilai Evaluasi (Float / Integer 0-100) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                        Nilai Proposal (Skala 0 - 100) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           step="0.1" 
                           min="0" 
                           max="100" 
                           name="nilai" 
                           x-model="editNilai" 
                           required 
                           placeholder="Contoh: 85.5"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-[#00295f] focus:outline-hidden">
                    <p class="text-[10px] text-gray-400 mt-1">Standar kelayakan lolos LPPM umumnya nilai ≥ 70.</p>
                </div>

                <!-- Status Penilaian (Enum: pending, revisi, diterima, ditolak) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                        Status Penilaian <span class="text-red-500">*</span>
                    </label>
                    <select name="status_penilaian" 
                            x-model="editStatus" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-[#00295f] focus:outline-hidden">
                        <option value="pending">Pending (Menunggu)</option>
                        <option value="revisi">Revisi (Perlu Perbaikan Pengusul)</option>
                        <option value="diterima">Diterima (Lolos Pendanaan)</option>
                        <option value="ditolak">Ditolak (Tidak Didanai)</option>
                    </select>
                </div>

                <!-- Catatan Revisi (Text) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                        Catatan Evaluasi / Catatan Revisi
                    </label>
                    <textarea name="catatan_revisi" 
                              rows="4" 
                              x-model="editCatatan" 
                              placeholder="Tuliskan catatan perbaikan substansi, metodologi, kelayakan RAB anggaran, atau rekomendasi perbaikan..."
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-[#00295f] focus:outline-hidden"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" 
                            @click="closeModal()" 
                            class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-xs font-semibold cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-[#00295f] hover:bg-blue-900 text-white text-xs font-bold shadow-md cursor-pointer inline-flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Hasil Penilaian</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
