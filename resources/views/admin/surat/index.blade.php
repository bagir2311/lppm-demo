@extends('admin.layouts.app')

@section('title', 'Layanan Persuratan Resmi - Admin LPPM')
@section('page-title', 'Layanan Persuratan LPPM')

@section('content')
<div class="space-y-6" x-data="{ detailModal: false, approveModal: false, rejectModal: false, selectedSurat: {}, defaultNomorSurat: '{{ $autoNomorSurat }}' }">

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
                <span>✉️ Otomatisasi Persuratan LPPM</span>
            </div>
            <h2 class="font-primary font-bold text-xl text-gray-900">Meja Kerja Verifikasi &amp; Penerbitan Surat Resmi</h2>
            <p class="text-xs text-gray-600">Verifikasi pengajuan surat izin penelitian, pengantar studi pendahuluan, dan surat tugas pengabdian dosen.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-200 text-right">
                <span class="text-[10px] uppercase font-bold text-gray-500 block">Total Surat</span>
                <span class="text-lg font-extrabold text-blue-900 font-mono">{{ $totalSurat }}</span>
            </div>
        </div>
    </div>

    <!-- Summary Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Menunggu Persetujuan</span>
                <h3 class="font-primary font-bold text-2xl text-amber-600 mt-1">{{ $pendingSurat }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                ⏳
            </div>
        </div>

        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Disetujui &amp; Diterbitkan</span>
                <h3 class="font-primary font-bold text-2xl text-emerald-700 mt-1">{{ $approvedSurat }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                ✓
            </div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <h3 class="font-primary font-bold text-sm text-gray-900">Daftar Pengajuan Surat Masuk</h3>
            </div>
            <span class="text-xs text-gray-500">Total: {{ $surats->count() }} Pengajuan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold text-gray-600 uppercase tracking-wider">
                        <th class="py-4 px-6">Jenis &amp; Nomor Surat</th>
                        <th class="py-4 px-6">Dosen Pemohon</th>
                        <th class="py-4 px-6">Keperluan &amp; Instansi Tujuan</th>
                        <th class="py-4 px-6">Status Pengajuan</th>
                        <th class="py-4 px-6 text-right">Aksi Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs text-gray-800">
                    @forelse($surats as $item)
                        @php
                            $rawAnggota = $item->getRawOriginal('anggota');
                            $parsedAnggota = [];
                            if (!empty($rawAnggota)) {
                                $parsedAnggota = is_string($rawAnggota) ? json_decode($rawAnggota, true) : $rawAnggota;
                            }
                            $suratPayload = [
                                'id' => $item->id,
                                'nama_dosen' => $item->nama_dosen,
                                'nidn' => $item->nidn ?? '-',
                                'prodi' => $item->prodi ?? 'Fakultas Kesehatan',
                                'jenis_surat' => $item->jenis_surat,
                                'keperluan' => $item->keperluan,
                                'instansi_tujuan' => $item->instansi_tujuan,
                                'nomor_surat' => $item->nomor_surat ?: $autoNomorSurat,
                                'status' => $item->status,
                                'created_at' => $item->created_at ? $item->created_at->format('d M Y, H:i') : '-',
                                'anggota' => is_array($parsedAnggota) ? $parsedAnggota : [],
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <!-- Jenis & Nomor Surat -->
                            <td class="py-4 px-6 max-w-xs">
                                <span style="display: inline-block; padding: 4px 8px; border-radius: 6px; font-weight: bold; font-size: 11px; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">
                                    {{ $item->jenis_surat }}
                                </span>
                                @if($item->nomor_surat)
                                    <div class="font-mono text-[11px] font-bold text-gray-900 mt-1">
                                        No: {{ $item->nomor_surat }}
                                    </div>
                                @else
                                    <div class="text-[10px] text-gray-500 italic mt-1">
                                        (Nomor surat dibuat otomatis saat disetujui)
                                    </div>
                                @endif
                                <div class="text-[10px] text-gray-500 mt-0.5">
                                    Diajukan: {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                                </div>
                            </td>

                            <!-- Dosen Pemohon -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900">{{ $item->nama_dosen }}</div>
                                <div class="text-[11px] text-gray-500">NIDN: {{ $item->nidn ?? '-' }}</div>
                                <div style="color: #1d4ed8; font-size: 10px;">{{ $item->prodi ?? 'Fakultas Kesehatan' }}</div>
                            </td>

                            <!-- Keperluan & Instansi -->
                            <td class="py-4 px-6 max-w-sm">
                                <div class="font-semibold text-gray-900 line-clamp-2">
                                    {{ $item->keperluan }}
                                </div>
                                <div class="text-[11px] text-gray-600 mt-1 flex items-center gap-1">
                                    <span class="font-medium text-gray-500">Tujuan:</span>
                                    <span class="line-clamp-1 font-semibold">{{ $item->instansi_tujuan }}</span>
                                </div>
                            </td>

                            <!-- Status Pengajuan -->
                            <td class="py-4 px-6">
                                @if(in_array(strtolower($item->status), ['approved', 'disetujui']))
                                    <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px; display: inline-block;">
                                        Disetujui
                                    </span>
                                    @if($item->approved_at)
                                        <div class="text-[10px] text-gray-500 mt-1">
                                            Rilis: {{ \Carbon\Carbon::parse($item->approved_at)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                @elseif(in_array(strtolower($item->status), ['rejected', 'ditolak']))
                                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px; display: inline-block;">
                                        Ditolak
                                    </span>
                                @else
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 10px; display: inline-block;">
                                        Menunggu Persetujuan
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Aksi Dokumen -->
                            <td class="py-4 px-6 text-right space-x-2 shrink-0 whitespace-nowrap">
                                <!-- Tombol Biru: 🔍 Cek Detail (Khusus Pending) -->
                                @if(!in_array(strtolower($item->status), ['approved', 'disetujui', 'rejected', 'ditolak']))
                                    <button type="button" 
                                            @click="selectedSurat = {{ json_encode($suratPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}; detailModal = true"
                                            style="background: #2563eb; color: white; padding: 6px 14px; border-radius: 8px; font-weight: bold; font-size: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;"
                                            class="hover:bg-blue-700 transition shadow-xs">
                                        <span>🔍 Cek Detail</span>
                                    </button>
                                @endif

                                <!-- Tombol Cetak A4 & Download PDF (Jika sudah disetujui) -->
                                @if(in_array(strtolower($item->status), ['approved', 'disetujui']))
                                    <button type="button" 
                                            @click="selectedSurat = {{ json_encode($suratPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}; detailModal = true"
                                            style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 6px 10px; border-radius: 8px; font-weight: bold; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;"
                                            class="hover:bg-blue-100 transition"
                                            title="Lihat Detail Permohonan">
                                        <span>🔍 Detail</span>
                                    </button>
                                    <a href="{{ route('surat.cetak', $item->id) }}" target="_blank" style="background: #047857; color: white; padding: 6px 12px; border-radius: 8px; font-weight: bold; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak A4
                                    </a>
                                    <a href="{{ route('surat.pdf', $item->id) }}" target="_blank" style="background: #1e3a8a; color: white; padding: 6px 12px; border-radius: 8px; font-weight: bold; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        PDF
                                    </a>
                                @endif

                                @if(in_array(strtolower($item->status), ['rejected', 'ditolak']))
                                    <button type="button" 
                                            @click="selectedSurat = {{ json_encode($suratPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}; detailModal = true"
                                            style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 10px; border-radius: 8px; font-weight: bold; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;"
                                            class="hover:bg-red-100 transition"
                                            title="Lihat Detail Permohonan">
                                        <span>🔍 Detail</span>
                                    </button>
                                @endif

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.surat.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip pengajuan surat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #dc2626; color: white; padding: 6px 10px; border-radius: 8px; font-weight: bold; font-size: 12px; border: none; cursor: pointer;" title="Hapus Riwayat Surat">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center" style="color: #6b7280; font-weight: bold;">
                                Belum Ada Pengajuan Surat Masuk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 1. MODAL PREVIEW / DETAIL PENGAJUAN SURAT (ADMIN) -->
    <div x-show="detailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-gray-200 overflow-hidden" @click.away="detailModal = false">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 bg-gray-50/75 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-lg shadow-xs">
                        📄
                    </div>
                    <div>
                        <h3 class="font-primary font-bold text-base text-gray-900">Detail Permohonan Surat Akademik</h3>
                        <p class="text-[11px] text-gray-500">Periksa kelengkapan usulan dan data instansi sebelum diverifikasi</p>
                    </div>
                </div>
                <button type="button" @click="detailModal = false" class="text-gray-400 hover:text-gray-600 cursor-pointer text-xl p-1 leading-none">&times;</button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 space-y-5 overflow-y-auto">
                <!-- Status Bar -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50/60 border border-blue-100 text-xs">
                    <span class="text-gray-600 font-medium">Status Pengajuan:</span>
                    <span class="font-bold px-3 py-1 rounded-full text-[11px]"
                          :class="{
                              'bg-amber-100 text-amber-800 border border-amber-200': ['pending', 'Pending'].includes(selectedSurat.status),
                              'bg-emerald-100 text-emerald-800 border border-emerald-200': ['approved', 'disetujui'].includes(selectedSurat.status),
                              'bg-red-100 text-red-800 border border-red-200': ['rejected', 'ditolak'].includes(selectedSurat.status)
                          }"
                          x-text="['approved', 'disetujui'].includes(selectedSurat.status) ? 'Disetujui' : (['rejected', 'ditolak'].includes(selectedSurat.status) ? 'Ditolak' : 'Menunggu Persetujuan')">
                    </span>
                </div>

                <!-- Bagian 1: Data Dosen Pemohon -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span>👨‍🏫 Bagian 1: Info Dosen Pemohon</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200/80 text-xs">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-500 block mb-0.5">Nama Lengkap &amp; Gelar</span>
                            <span class="font-bold text-gray-900 text-sm block" x-text="selectedSurat.nama_dosen"></span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-500 block mb-0.5">NIDN / NIP</span>
                            <span class="font-mono font-semibold text-gray-800 block" x-text="selectedSurat.nidn || '-'"></span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-500 block mb-0.5">Program Studi / Fakultas</span>
                            <span class="font-semibold text-blue-700 block" x-text="selectedSurat.prodi || '-'"></span>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Info Surat & Keperluan -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span>📑 Bagian 2: Info Surat &amp; Instansi Tujuan</span>
                    </h4>
                    <div class="space-y-3 p-4 bg-gray-50 rounded-xl border border-gray-200/80 text-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-200 pb-3">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-500 block mb-0.5">Jenis Dokumen Surat</span>
                                <span class="inline-block px-3 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-900 border border-blue-200" x-text="selectedSurat.jenis_surat"></span>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-[10px] uppercase font-bold text-gray-500 block mb-0.5">Tanggal Pengajuan</span>
                                <span class="font-semibold text-gray-700" x-text="selectedSurat.created_at"></span>
                            </div>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-500 block mb-1">Keperluan / Judul Penelitian / Agenda Riset:</span>
                            <div class="p-3 bg-white border border-gray-200 rounded-lg text-xs text-gray-900 leading-relaxed font-medium whitespace-pre-line shadow-2xs" x-text="selectedSurat.keperluan"></div>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-500 block mb-1">Instansi / Lokasi Sasaran Tujuan:</span>
                            <div class="p-3 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-900 flex items-center gap-2 shadow-2xs">
                                <span class="text-red-500 text-base leading-none">📍</span>
                                <span x-text="selectedSurat.instansi_tujuan"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 3: Anggota Tim Peneliti (Jika Ada) -->
                <div x-show="selectedSurat.anggota && selectedSurat.anggota.length > 0" class="space-y-2">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span>👥 Bagian 3: Anggota Tim Peneliti (Kolektif / Kasbangpol)</span>
                        </h4>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 font-mono" x-text="(selectedSurat.anggota ? selectedSurat.anggota.length : 0) + ' Anggota'"></span>
                    </div>

                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-white shadow-xs">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-gray-100/90 text-gray-700 text-[11px] font-bold border-b border-gray-200">
                                <tr>
                                    <th class="py-2.5 px-3 w-12 text-center">No</th>
                                    <th class="py-2.5 px-4">Nama Anggota</th>
                                    <th class="py-2.5 px-4">Judul Penelitian / Subtopik</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-800">
                                <template x-for="(item, index) in selectedSurat.anggota" :key="index">
                                    <tr class="hover:bg-blue-50/40 transition-colors">
                                        <td class="py-2.5 px-3 text-center text-gray-500 font-mono font-bold" x-text="index + 1"></td>
                                        <td class="py-2.5 px-4 font-semibold text-gray-900" x-text="item.nama || '-'"></td>
                                        <td class="py-2.5 px-4 text-gray-600 italic" x-text="item.judul || selectedSurat.keperluan"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (3 Tombol) -->
            <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200 shrink-0">
                <!-- a. Tombol Tutup -->
                <button type="button" 
                        @click="detailModal = false" 
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-bold rounded-xl transition cursor-pointer">
                    Tutup
                </button>

                <div class="flex items-center gap-2">
                    <template x-if="!['approved', 'disetujui'].includes(selectedSurat.status)">
                        <div class="flex items-center gap-2">
                            <!-- b. Tombol Merah: Tolak Pengajuan -->
                            <button type="button" 
                                    @click="detailModal = false; rejectModal = true" 
                                    style="background: #dc2626; color: white; padding: 7px 14px; border-radius: 10px; font-weight: bold; font-size: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;"
                                    class="hover:bg-red-700 transition shadow-xs">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Tolak Pengajuan</span>
                            </button>

                            <!-- c. Tombol Hijau: Setujui & Terbitkan -->
                            <button type="button" 
                                    @click="detailModal = false; approveModal = true" 
                                    style="background: #059669; color: white; padding: 7px 16px; border-radius: 10px; font-weight: bold; font-size: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;"
                                    class="hover:bg-emerald-700 transition shadow-xs">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Setujui &amp; Terbitkan</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MODAL TOLAK PENGAJUAN SURAT (ADMIN) -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-gray-200">
            <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                <div class="flex items-center gap-2 text-red-600">
                    <span class="text-xl">⚠️</span>
                    <h3 class="font-primary font-bold text-base text-gray-900">Tolak Permohonan Surat</h3>
                </div>
                <button type="button" @click="rejectModal = false; detailModal = true" class="text-gray-400 hover:text-gray-600 cursor-pointer text-lg">&times;</button>
            </div>

            <form :action="'{{ url('admin/surat') }}/' + selectedSurat.id + '/reject'" method="POST" class="space-y-4">
                @csrf
                <p class="text-xs text-gray-600 leading-relaxed">
                    Anda akan menolak pengajuan <strong class="text-gray-900" x-text="selectedSurat.jenis_surat"></strong> dari dosen <strong class="text-gray-900" x-text="selectedSurat.nama_dosen"></strong>.
                </p>

                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-1">
                        Catatan / Alasan Penolakan (Opsional)
                    </label>
                    <textarea name="alasan_penolakan" rows="3" placeholder="Contoh: Lampiran proposal atau instansi tujuan belum lengkap, mohon ajukan ulang..." class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-red-500 focus:border-red-500"></textarea>
                    <p class="text-[11px] text-gray-500 mt-1">Alasan penolakan ini akan tampil pada riwayat notifikasi dosen.</p>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200">
                    <button type="button" @click="rejectModal = false; detailModal = true" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition cursor-pointer">Konfirmasi Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. MODAL SETUJUI & TERBITKAN SURAT (ADMIN) -->
    <div x-show="approveModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-gray-200">
            <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                <h3 class="font-primary font-bold text-base text-gray-900">Persetujuan &amp; Penerbitan Surat Resmi</h3>
                <button type="button" @click="approveModal = false; detailModal = true" class="text-gray-400 hover:text-gray-600 cursor-pointer text-lg">&times;</button>
            </div>

            <form :action="'{{ url('admin/surat') }}/' + selectedSurat.id + '/approve'" method="POST" class="space-y-4">
                @csrf
                <div class="p-3 bg-blue-50 rounded-xl text-xs text-blue-900 font-medium space-y-1">
                    <div>Pemohon: <span x-text="selectedSurat.nama_dosen" class="font-bold"></span></div>
                    <div>Keperluan: <span x-text="selectedSurat.keperluan" class="italic"></span></div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-gray-900">
                            Nomor Surat Resmi
                        </label>
                        <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-bold">
                            Otomatis Terisi
                        </span>
                    </div>
                    <input type="text" 
                           name="nomor_surat" 
                           x-model="selectedSurat.nomor_surat"
                           placeholder="Contoh: 0019/LPPM UIM/X/2026" 
                           class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f]">
                    <p class="text-[11px] text-gray-500 mt-1">
                        Format Resmi: [No. Urut 4 Digit]/LPPM UIM/[Bulan Romawi]/[Tahun]
                    </p>
                </div>

                <p class="text-xs text-gray-500 leading-relaxed">
                    Sistem akan otomatis menghasilkan <strong>QR Code e-Signature</strong> bertanda tangan digital Kepala LPPM berdasar hash MD5 terverifikasi.
                </p>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200">
                    <button type="button" @click="approveModal = false; detailModal = true" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition cursor-pointer">Setujui &amp; Terbitkan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
