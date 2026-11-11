@extends('admin.layouts.app')
@section('title', 'Meja Kerja Admin Penelitian - Usulan Proposal')
@section('page-title', 'Meja Kerja Admin Penelitian')
@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-xs font-semibold flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button @click="$el.parentElement.remove()" class="text-green-600 hover:text-green-900">×</button>
    </div>
    @endif
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 text-blue-900 text-xs font-bold uppercase tracking-wider">
                <span>🔬 Meja Kerja Penelitian (BIMA LPPM)</span>
            </div>
            <h2 class="font-primary font-bold text-xl text-gray-900">Daftar Usulan Proposal Penelitian Masuk</h2>
            <p class="text-xs text-gray-600">Kelola usulan hibah penelitian dosen, verifikasi dokumen, dan pantau kelengkapan RAB.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-200 text-right">
                <span class="text-[10px] uppercase font-bold text-gray-500 block">Total Usulan</span>
                <span class="text-lg font-extrabold text-blue-900 font-mono">{{ $proposals->count() }}</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <h3 class="font-primary font-bold text-sm text-gray-900">Daftar Berkas Usulan Masuk</h3>
            </div>
            <span class="text-xs text-gray-500">Menampilkan seluruh proposal skema Penelitian</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-4 px-6">Judul Proposal</th>
                        <th class="py-4 px-6">Nama Dosen / NIDN</th>
                        <th class="py-4 px-6">Skema</th>
                        <th class="py-4 px-6">Status & Skor</th>
                        <th class="py-4 px-6 text-right">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs text-gray-800">
                    @forelse($proposals as $proposal)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 font-semibold max-w-sm">
                                <div class="text-gray-900 font-bold leading-relaxed line-clamp-2">{{ $proposal->judul }}</div>
                                <div class="text-[10px] text-gray-500 mt-1 flex items-center gap-2">
                                    <span>Diajukan: {{ $proposal->created_at ? $proposal->created_at->format('d M Y, H:i') : '-' }}</span>
                                    @if($proposal->periode)
                                        <span style="background: #e5e7eb; color: #374151; padding: 2px 6px; border-radius: 4px;">{{ $proposal->periode->nama_periode }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900">{{ $proposal->dosen->nama ?? 'Dosen Insan Mahardika' }}</div>
                                <div class="text-[11px] text-gray-600">NIDN: {{ $proposal->dosen->nidn ?? '-' }}</div>
                                <div style="color: #1d4ed8; font-size: 10px;">{{ $proposal->dosen->prodi ?? 'Fakultas Kesehatan' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; padding: 4px 8px; border-radius: 6px; font-weight: bold; display: inline-block;">
                                    🔬 {{ $proposal->skema }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($proposal->status === 'submitted')
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">Submitted</span>
                                @elseif($proposal->status === 'in_review')
                                    <span style="background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">In Review</span>
                                @elseif($proposal->status === 'approved')
                                    <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">Approved</span>
                                @elseif($proposal->status === 'rejected')
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">Rejected</span>
                                @else
                                    <span style="background: #e5e7eb; color: #374151; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">{{ ucfirst($proposal->status) }}</span>
                                @endif

                                @if($proposal->nilai_total !== null)
                                    <div style="color: #374151; font-weight: bold; margin-top: 6px; font-size: 10px;">
                                        Skor: {{ $proposal->nilai_total }}/100 ({{ $proposal->rekomendasi ?? '-' }})
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 shrink-0">
                                @if($proposal->status === 'submitted')
                                    <form action="{{ route('admin.penelitian.assign', $proposal->id) }}" method="POST" style="display: inline-flex; align-items: center; gap: 6px; padding: 4px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                                        @csrf
                                        <select name="reviewer_id" required style="font-size: 12px; padding: 4px 8px; border-radius: 6px; border: 1px solid #d1d5db; outline: none; cursor: pointer;">
                                            <option value="">-- Pilih Reviewer --</option>
                                            @foreach($reviewers as $rev)
                                                <option value="{{ $rev->id }}">{{ $rev->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" style="background: #2563eb; color: white; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; border: none; cursor: pointer;">
                                            Assign
                                        </button>
                                    </form>
                                @elseif($proposal->status === 'in_review')
                                    <span style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: bold; display: inline-flex; gap: 4px;">
                                        Reviewer: {{ $proposal->reviewer->name ?? 'Ditugaskan' }}
                                    </span>
                                @endif

                                @if($proposal->status !== 'approved')
                                    <form action="{{ route('admin.penelitian.approve', $proposal->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menyetujui proposal ini?');">
                                        @csrf
                                        <button type="submit" style="background: #059669; color: white; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; border: none; cursor: pointer;">Setujui</button>
                                    </form>
                                @endif

                                @if($proposal->file_proposal)
                                    <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank" download style="background: #1e3a8a; color: white; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Proposal
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center" style="color: #6b7280; font-weight: bold;">
                                Belum Ada Usulan Penelitian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
