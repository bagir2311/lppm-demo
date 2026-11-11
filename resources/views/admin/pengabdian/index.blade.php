@extends('admin.layouts.app')

@section('title', 'Meja Kerja Admin Pengabdian - Usulan Proposal')
@section('page-title', 'Meja Kerja Admin Pengabdian')

@section('content')
<div class="space-y-6">

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/30 dark:border-green-800 dark:text-green-300 text-xs font-semibold flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button @click="$el.parentElement.remove()" class="text-green-600 hover:text-green-900">×</button>
    </div>
    @endif

    <!-- Header & Summary Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-dark-surface p-6 rounded-2xl border border-border-main dark:border-dark-border shadow-card">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider">
                <span>🤝 Meja Kerja Pengabdian Masyarakat (BIMA LPPM)</span>
            </div>
            <h2 class="font-primary font-bold text-xl text-text-main dark:text-white">Daftar Usulan Proposal Pengabdian Masuk</h2>
            <p class="text-xs text-text-secondary dark:text-dark-text-muted">Kelola usulan pengabdian kepada masyarakat (PKM), periksa kelengkapan proposal dan berkas anggaran RAB.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-bg-alt dark:bg-dark-surface-soft px-4 py-2.5 rounded-xl border border-border-main dark:border-dark-border text-right">
                <span class="text-[10px] uppercase font-bold text-text-secondary dark:text-dark-text-muted block">Total Usulan</span>
                <span class="text-lg font-extrabold text-emerald-700 dark:text-emerald-400 font-mono">{{ $proposals->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-dark-surface rounded-2xl border border-border-main dark:border-dark-border shadow-card overflow-hidden">
        <div class="p-5 border-b border-border-main dark:border-dark-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="font-primary font-bold text-sm text-text-main dark:text-white">Daftar Berkas Usulan Masuk</h3>
            </div>
            <span class="text-xs text-text-secondary dark:text-dark-text-muted">Menampilkan seluruh proposal skema Pengabdian</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-alt dark:bg-dark-surface-soft border-b border-border-main dark:border-dark-border text-[11px] font-bold text-text-secondary dark:text-dark-text-muted uppercase tracking-wider">
                        <th class="py-4 px-6">Judul Proposal</th>
                        <th class="py-4 px-6">Nama Dosen / NIDN</th>
                        <th class="py-4 px-6">Skema</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-main dark:divide-dark-border text-xs text-text-main dark:text-dark-text-muted">
                    @forelse($proposals as $proposal)
                        <tr class="hover:bg-bg-alt/50 dark:hover:bg-dark-surface-soft/40 transition-colors">
                            <!-- Judul Proposal -->
                            <td class="py-4 px-6 font-semibold max-w-sm">
                                <div class="text-text-main dark:text-white font-bold leading-relaxed line-clamp-2">
                                    {{ $proposal->judul }}
                                </div>
                                <div class="text-[10px] text-text-secondary dark:text-dark-text-muted mt-1 flex items-center gap-2">
                                    <span>Diajukan: {{ $proposal->created_at ? $proposal->created_at->format('d M Y, H:i') : '-' }}</span>
                                    @if($proposal->periode)
                                        <span class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-[10px] font-medium text-gray-700 dark:text-gray-300">
                                            {{ $proposal->periode->nama_periode }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Nama Dosen & NIDN -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-text-main dark:text-white">
                                    {{ $proposal->dosen->nama ?? 'Dosen Insan Mahardika' }}
                                </div>
                                <div class="text-[11px] text-text-secondary dark:text-dark-text-muted">
                                    NIDN: {{ $proposal->dosen->nidn ?? '-' }}
                                </div>
                                <div class="text-[10px] text-emerald-700 dark:text-emerald-400">
                                    {{ $proposal->dosen->prodi ?? 'Fakultas Kesehatan' }}
                                </div>
                            </td>

                            <!-- Skema -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    🤝 {{ $proposal->skema }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                @if($proposal->status === 'submitted')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                        Submitted
                                    </span>
                                @elseif($proposal->status === 'in_review')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                        In Review
                                    </span>
                                @elseif($proposal->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        Approved
                                    </span>
                                @elseif($proposal->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ ucfirst($proposal->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Aksi Download & Persetujuan -->
                            <td class="py-4 px-6 text-right space-x-2 shrink-0">
                                @if($proposal->status !== 'approved')
                                    <form action="{{ route('admin.pengabdian.approve', $proposal->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menyetujui proposal ini?');">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-700 transition cursor-pointer">Setujui</button>
                                    </form>
                                @endif

                                @if($proposal->file_proposal)
                                    <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-mahardika-navy hover:bg-blue-900 text-white rounded-lg text-xs font-semibold shadow-xs transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Download Proposal</span>
                                    </a>
                                @endif

                                @if($proposal->file_rab)
                                    <a href="{{ asset('storage/' . $proposal->file_rab) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold shadow-xs transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Download RAB</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-text-secondary dark:text-dark-text-muted">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 flex items-center justify-center mx-auto text-xl">
                                        🤝
                                    </div>
                                    <p class="font-bold text-text-main dark:text-white text-sm">Belum Ada Usulan Pengabdian</p>
                                    <p class="text-xs">Saat ini belum ada dosen yang mengajukan usulan proposal kegiatan untuk skema Pengabdian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
