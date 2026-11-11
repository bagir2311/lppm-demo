@extends('admin.layouts.app')

@section('title', 'Verifikasi Berkas & LPJ Dosen - Admin LPPM')
@section('page-title', 'Verifikasi Pengumpulan Berkas & LPJ')

@section('content')
<div class="space-y-6" x-data="{ revisiModal: false, selectedBerkas: {} }">

    <!-- Flash Message -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-xs font-semibold flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button @click="$el.parentElement.remove()" class="text-green-600 hover:text-green-900 cursor-pointer">×</button>
    </div>
    @endif

    <!-- Header Page Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 text-blue-900 text-xs font-bold uppercase tracking-wider">
                <span>📂 Pengelolaan Berkas &amp; LPJ</span>
            </div>
            <h2 class="font-primary font-bold text-xl text-gray-900">Verifikasi Dokumen &amp; Laporan Pertanggungjawaban (LPJ)</h2>
            <p class="text-xs text-gray-600">Periksa berkas proposal, laporan kemajuan, laporan akhir, dan bukti LPJ keuangan yang diunggah oleh dosen.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-200 text-right">
                <span class="text-[10px] uppercase font-bold text-gray-500 block">Total Dokumen</span>
                <span class="text-lg font-extrabold text-blue-900 font-mono">{{ $totalBerkas }}</span>
            </div>
        </div>
    </div>

    <!-- Summary Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Disetujui / Valid</span>
                <h3 class="font-primary font-bold text-2xl text-emerald-700 mt-1">{{ $approvedBerkas }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                ✓
            </div>
        </div>

        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Menunggu Verifikasi</span>
                <h3 class="font-primary font-bold text-2xl text-amber-600 mt-1">{{ $pendingBerkas }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                ⏳
            </div>
        </div>

        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Perlu Revisi / Ditolak</span>
                <h3 class="font-primary font-bold text-2xl text-red-600 mt-1">{{ $revisionBerkas }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                ✕
            </div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <h3 class="font-primary font-bold text-sm text-gray-900">Daftar Berkas Terkumpul</h3>
            </div>
            <span class="text-xs text-gray-500">Total: {{ $berkasList->count() }} Berkas</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-4 px-6">Jenis Dokumen</th>
                        <th class="py-4 px-6">Nama File</th>
                        <th class="py-4 px-6">Dosen Pengunggah</th>
                        <th class="py-4 px-6">Judul Riset / PKM</th>
                        <th class="py-4 px-6">Status Review</th>
                        <th class="py-4 px-6 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs text-gray-800">
                    @forelse($berkasList as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <!-- Jenis Dokumen -->
                            <td class="py-4 px-6">
                                <span style="display: inline-block; padding: 4px 8px; border-radius: 6px; font-weight: bold; font-size: 11px; {{ $item->jenis_berkas === 'LPJ Keuangan' ? 'background: #fee2e2; color: #991b1b;' : 'background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;' }}">
                                    {{ $item->jenis_berkas }}
                                </span>
                            </td>

                            <!-- Nama File & Ukuran -->
                            <td class="py-4 px-6 max-w-xs">
                                <div class="font-bold text-gray-900 flex items-center space-x-1.5">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="line-clamp-1" title="{{ $item->nama_file }}">{{ $item->nama_file }}</span>
                                </div>
                                <div class="text-[10px] text-gray-500 mt-0.5">Ukuran: {{ $item->ukuran_file ?? '-' }}</div>
                            </td>

                            <!-- Dosen Pengunggah -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900">{{ $item->dosen->nama ?? 'Dosen Insan Mahardika' }}</div>
                                <div class="text-[11px] text-gray-500">NIDN: {{ $item->dosen->nidn ?? '-' }}</div>
                                <div style="color: #1d4ed8; font-size: 10px;">{{ $item->dosen->prodi ?? 'Fakultas Kesehatan' }}</div>
                            </td>

                            <!-- Judul Riset / Pengabdian -->
                            <td class="py-4 px-6 max-w-xs">
                                <div class="line-clamp-2 font-semibold text-gray-900">
                                    {{ $item->penelitian->judul ?? $item->pengabdian->judul ?? 'Pengajuan Hibah Riset & PKM' }}
                                </div>
                            </td>

                            <!-- Status Review -->
                            <td class="py-4 px-6">
                                @if(in_array(strtolower($item->status), ['approved', 'disetujui']))
                                    <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">
                                        Approved
                                    </span>
                                @elseif(in_array(strtolower($item->status), ['revisi']))
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">
                                        Perlu Revisi
                                    </span>
                                @elseif(in_array(strtolower($item->status), ['rejected', 'ditolak']))
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">
                                        Rejected
                                    </span>
                                @else
                                    <span style="background: #e5e7eb; color: #374151; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px;">
                                        Submitted
                                    </span>
                                @endif

                                @if($item->catatan)
                                    <div class="text-[10px] text-gray-600 italic mt-1 line-clamp-1 max-w-[200px]" title="{{ $item->catatan }}">
                                        "{{ $item->catatan }}"
                                    </div>
                                @endif
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="py-4 px-6 text-right space-x-2 shrink-0 whitespace-nowrap">
                                <!-- Tombol Setujui -->
                                @if(!in_array(strtolower($item->status), ['approved', 'disetujui']))
                                    <form action="{{ route('admin.berkas.updateStatus', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menyetujui berkas ini?');">
                                        @csrf
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" style="background: #059669; color: white; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; border: none; cursor: pointer;">
                                            Setujui
                                        </button>
                                    </form>
                                @endif

                                <!-- Tombol Minta Revisi / Ubah Status -->
                                <button type="button" 
                                        @click="selectedBerkas = {{ json_encode($item) }}; revisiModal = true" 
                                        style="background: #d97706; color: white; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; border: none; cursor: pointer;">
                                    Revisi / Tolak
                                </button>

                                <!-- Tombol Unduh Berkas -->
                                @if(!empty($item->file_path))
                                    <a href="{{ asset($item->file_path) }}" target="_blank" download style="background: #1e3a8a; color: white; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Unduh
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center" style="color: #6b7280; font-weight: bold;">
                                Belum Ada Berkas yang Diunggah oleh Dosen
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Evaluasi & Ubah Status Berkas -->
    <div x-show="revisiModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-gray-200">
            <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                <h3 class="font-primary font-bold text-base text-gray-900">Ubah Status &amp; Catatan Verifikasi</h3>
                <button type="button" @click="revisiModal = false" class="text-gray-400 hover:text-gray-600 cursor-pointer font-bold text-lg">✕</button>
            </div>

            <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-xs font-bold text-blue-900" x-text="selectedBerkas.nama_file"></p>
                <p class="text-[10px] text-gray-600 mt-0.5">Pengunggah: <span class="font-semibold" x-text="selectedBerkas.dosen?.nama ?? 'Dosen'"></span></p>
            </div>

            <form :action="'{{ url('admin/berkas') }}/' + selectedBerkas.id + '/status'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-1">Pilih Status *</label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs bg-white text-gray-900 focus:ring-1 focus:ring-blue-900">
                        <option value="Approved">Approved (Disetujui &amp; Valid)</option>
                        <option value="Revisi">Revisi (Perlu Perbaikan / Nota Kurang)</option>
                        <option value="Rejected">Rejected (Ditolak)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-1">Catatan Verifikasi / Evaluasi LPPM</label>
                    <textarea name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs bg-white text-gray-900 focus:ring-1 focus:ring-blue-900" placeholder="Keterangan kelengkapan nota belanja, format berkas, dsb..."></textarea>
                </div>
<div style="display: flex; items-center; justify-content: flex-end; gap: 10px; padding-top: 12px; margin-top: 16px; border-top: 1px solid #e5e7eb;">
    <button type="button" @click="revisiModal = false" style="background: #f3f4f6; color: #374151; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 12px; border: none; cursor: pointer;">
        Batal
    </button>
    <button type="submit" style="background: #1e3a8a; color: #ffffff; padding: 8px 18px; border-radius: 8px; font-weight: 700; font-size: 12px; border: none; cursor: pointer;">
        Simpan Status
    </button>
</div>
@endsection
