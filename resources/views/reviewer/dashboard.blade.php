@extends('layouts.app')

@section('title', 'Dashboard Reviewer Proposal - LPPM Mahardika')

@section('content')
<div class="pt-28 pb-16 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ reviewModal: false, selectedProposal: {} }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Page -->
        <div class="bg-gradient-to-r from-[#00295f] to-[#001d45] text-white p-6 sm:p-8 rounded-2xl shadow-sm space-y-2">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 text-xs font-bold uppercase tracking-wider">
                <span>⭐ Portal Reviewer Independen</span>
            </div>
            <h1 class="font-primary font-bold text-2xl sm:text-3xl">Dashboard Evaluasi &amp; Scoring Proposal LPPM</h1>
            <p class="text-xs text-blue-200">Panel penugasan reviewer independen untuk penilaian substansi ilmiah, kelayakan RAB, dan penetapan rekomendasi pendanaan proposal.</p>
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
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Total Ditugaskan</span>
                    <h3 class="font-primary font-bold text-2xl sm:text-3xl text-gray-900 dark:text-white mt-1">{{ $totalProposal }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center text-[#00295f] dark:text-blue-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Menunggu Penilaian</span>
                    <h3 class="font-primary font-bold text-2xl sm:text-3xl text-amber-600 dark:text-amber-400 mt-1">{{ $pendingProposal }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Evaluasi Selesai</span>
                    <h3 class="font-primary font-bold text-2xl sm:text-3xl text-green-700 dark:text-green-400 mt-1">{{ $completedProposal }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/40 flex items-center justify-center text-green-700 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
        </div>

        <!-- Assigned Proposals Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Daftar Usulan Proposal Yang Ditugaskan</h3>
                <span class="text-xs text-gray-500 font-semibold">Total: {{ $proposals->count() }} Usulan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold uppercase text-xs border-b border-gray-200 dark:border-gray-700">
                            <th class="py-4 px-6">Judul Proposal</th>
                            <th class="py-4 px-6">Dosen Pengusul</th>
                            <th class="py-4 px-6">Skema</th>
                            <th class="py-4 px-6">Status &amp; Skor</th>
                            <th class="py-4 px-6">Berkas Dokumen</th>
                            <th class="py-4 px-6 text-right">Aksi Penilaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        @forelse($proposals as $item)
                        <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-50/70 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white max-w-xs">
                                <div class="line-clamp-2 leading-relaxed">{{ $item->judul }}</div>
                                @if($item->periode)
                                    <div class="text-[10px] text-gray-500 mt-1 font-normal">Periode: {{ $item->periode->nama_periode }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $item->dosen->nama ?? 'Dosen Pengusul' }}</div>
                                <div class="text-[10px] text-gray-500">NIDN: {{ $item->dosen->nidn ?? '-' }}</div>
                                <div class="text-[10px] text-blue-600 dark:text-blue-400">{{ $item->dosen->prodi ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-[#00295f] dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    {{ $item->skema }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($item->nilai_total !== null)
                                    <div class="font-primary font-bold text-sm {{ $item->nilai_total >= 70 ? 'text-green-700 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        Skor: {{ $item->nilai_total }} / 100
                                    </div>
                                    <div class="text-[10px] font-semibold text-gray-600 dark:text-gray-300 mt-0.5">
                                        Rekomendasi: <span class="uppercase">{{ $item->rekomendasi ?? '-' }}</span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                        Belum Dinilai
                                    </span>
                                @endif
                                @if($item->catatan_reviewer)
                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 italic mt-1 line-clamp-1 max-w-[180px]">
                                        "{{ $item->catatan_reviewer }}"
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 space-y-1">
                                @if($item->file_proposal)
                                    <div>
                                        <a href="{{ asset('storage/' . $item->file_proposal) }}" target="_blank" download class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#00295f] dark:text-blue-400 hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Proposal PDF
                                        </a>
                                    </div>
                                @endif
                                @if($item->file_rab)
                                    <div>
                                        <a href="{{ asset('storage/' . $item->file_rab) }}" target="_blank" download class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            RAB File
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <button type="button" @click="selectedProposal = {{ json_encode($item) }}; reviewModal = true" 
                                        class="px-4 py-2 bg-[#00295f] hover:bg-blue-900 text-white rounded-xl text-xs font-semibold hover:opacity-90 transition cursor-pointer inline-flex items-center space-x-1 shadow-xs">
                                    <span>{{ $item->nilai_total !== null ? 'Perbarui Nilai' : 'Beri Nilai' }}</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 text-[#00295f] dark:text-blue-300 flex items-center justify-center mx-auto text-xl">
                                        ⭐
                                    </div>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">Belum Ada Proposal Ditugaskan</p>
                                    <p class="text-xs">Saat ini belum ada proposal penelitian yang di-assign oleh Admin kepada akun Reviewer Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Form Penilaian Reviewer -->
    <div x-show="reviewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Form Penilaian &amp; Evaluasi Proposal</h3>
                <button type="button" @click="reviewModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">✕</button>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-xl border border-blue-200 dark:border-blue-800">
                <p class="text-[11px] font-bold text-[#00295f] dark:text-blue-300" x-text="selectedProposal.judul"></p>
                <p class="text-[10px] text-gray-600 dark:text-gray-300 mt-0.5">Pengusul: <span x-text="selectedProposal.dosen?.nama ?? '-'"></span></p>
            </div>

            <form :action="'{{ url('reviewer/penilaian') }}/' + selectedProposal.id" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Nilai Total Proposal (Skala 0 - 100) *</label>
                    <input type="number" name="nilai_total" min="0" max="100" required :value="selectedProposal.nilai_total" placeholder="Contoh: 85" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Rekomendasi Hasil Penelaahan *</label>
                    <select name="rekomendasi" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="Diterima" :selected="selectedProposal.rekomendasi === 'Diterima'">Diterima (Layak Didanai)</option>
                        <option value="Revisi" :selected="selectedProposal.rekomendasi === 'Revisi'">Revisi (Perlu Perbaikan Minor/Mayor)</option>
                        <option value="Ditolak" :selected="selectedProposal.rekomendasi === 'Ditolak'">Ditolak (Tidak Layak Didanai)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Catatan Telaah / Umpan Balik untuk Dosen</label>
                    <textarea name="catatan_reviewer" rows="4" :value="selectedProposal.catatan_reviewer" placeholder="Tuliskan evaluasi substansi metodologi, kebaruan, atau catatan anggaran belanja proposal..." class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="reviewModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white text-xs font-bold rounded-xl hover:opacity-90 transition cursor-pointer">Simpan Hasil Penilaian</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
