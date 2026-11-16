@extends('admin.layouts.app')

@section('title', 'Verifikasi HKI & Hak Cipta - Admin LPPM')
@section('page-title', 'Verifikasi Pengajuan Sentra HKI')

@section('content')
<div class="space-y-6" x-data="{ 
    modalBerkas: false, 
    selectedHki: null,
    modalReject: false,
    rejectId: null,
    rejectJudul: ''
}">

    <!-- Flash Message -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center justify-between shadow-sm transition-colors duration-200">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button @click="$el.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-200 font-bold cursor-pointer">✕</button>
    </div>
    @endif

    <!-- Header Page Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-[#0f172a] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm transition-colors duration-200">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 text-xs font-bold uppercase tracking-wider">
                <span>💡 Sentra Hak Kekayaan Intelektual (HKI)</span>
            </div>
            <h2 class="font-primary font-bold text-xl text-gray-900 dark:text-white">Meja Kerja Verifikasi &amp; Persetujuan HKI</h2>
            <p class="text-xs text-gray-600 dark:text-gray-400">Verifikasi berkas persyaratan permohonan hak cipta dan paten dosen untuk pencatatan resmi DJKI Kemenkumham RI.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <div class="bg-gray-50 dark:bg-slate-800/80 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-right">
                <span class="text-[10px] uppercase font-bold text-gray-500 dark:text-gray-400 block">Total Pengajuan HKI</span>
                <span class="text-lg font-extrabold text-amber-600 dark:text-amber-400 font-mono">{{ $totalHki }}</span>
            </div>
        </div>
    </div>

    <!-- Pengaturan Koordinator Sentra HKI Card -->
    <div class="bg-white dark:bg-[#0f172a] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm transition-colors duration-200 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100 dark:border-gray-800">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Pengaturan Koordinator Sentra HKI</h3>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                    Ubah data Koordinator Sentra HKI langsung dari sini. Perubahan akan tampil otomatis pada formulir pendaftaran HKI dosen (<a href="{{ route('dosen.haki.create') }}" target="_blank" class="text-amber-600 dark:text-amber-400 font-semibold hover:underline inline-flex items-center gap-1">buka form dosen &rarr;</a>).
                </p>
            </div>

            <!-- Live Status & Preview Badge -->
            <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl text-xs font-semibold text-amber-900 dark:text-amber-200 shrink-0 sm:text-right">
                <span class="text-[10px] uppercase font-bold text-amber-700 dark:text-amber-400 block">Koordinator Aktif Saat Ini:</span>
                <div class="font-bold text-sm text-gray-900 dark:text-white mt-0.5">{{ \App\Models\Setting::get('haki_coordinator_name', $settings['haki_coordinator_name'] ?? 'Ibu Iffah') }}</div>
                @php
                    $activeContact = \App\Models\Setting::get('haki_coordinator_contact', $settings['haki_coordinator_contact'] ?? '');
                @endphp
                @if(!empty($activeContact))
                <div class="text-[10px] text-amber-800 dark:text-amber-300 font-normal mt-0.5">{{ $activeContact }}</div>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.settings.hakiCoordinator') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold uppercase text-[10px] text-gray-700 dark:text-gray-300 mb-1.5">
                        Nama Koordinator Sentra HKI <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="haki_coordinator_name" 
                           required 
                           value="{{ \App\Models\Setting::get('haki_coordinator_name', $settings['haki_coordinator_name'] ?? 'Ibu Iffah') }}" 
                           placeholder="Contoh: Ibu Iffah" 
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/30 text-gray-900 dark:text-white text-xs font-medium">
                </div>

                <div>
                    <label class="block font-bold uppercase text-[10px] text-gray-700 dark:text-gray-300 mb-1.5">
                        Kontak / Keterangan Layanan HKI (Opsional)
                    </label>
                    <input type="text" 
                           name="haki_coordinator_contact" 
                           value="{{ \App\Models\Setting::get('haki_coordinator_contact', $settings['haki_coordinator_contact'] ?? '') }}" 
                           placeholder="Contoh: (0231) 456-789 / lppm@mahardika.ac.id" 
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/30 text-gray-900 dark:text-white text-xs font-medium">
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <span class="text-[11px] text-gray-500 dark:text-gray-400 italic">
                    * Perubahan tersimpan secara global ke database konfigurasi LPPM.
                </span>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 active:scale-95 text-white font-bold text-xs flex items-center space-x-2 transition-all duration-150 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan & Perbarui Koordinator HKI</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Menunggu Verifikasi -->
        <div class="p-5 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm flex items-center justify-between transition-colors duration-200">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Menunggu Verifikasi</span>
                <h3 class="font-primary font-bold text-2xl text-amber-600 dark:text-amber-400 mt-1">{{ $pendingHki }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-300 flex items-center justify-center font-bold text-lg">
                ⏳
            </div>
        </div>

        <!-- Disetujui -->
        <div class="p-5 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm flex items-center justify-between transition-colors duration-200">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Disetujui / Terbit</span>
                <h3 class="font-primary font-bold text-2xl text-emerald-600 dark:text-emerald-400 mt-1">{{ $approvedHki }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-300 flex items-center justify-center font-bold text-lg">
                ✓
            </div>
        </div>

        <!-- Ditolak -->
        <div class="p-5 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm flex items-center justify-between transition-colors duration-200">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Ditolak / Belum Lengkap</span>
                <h3 class="font-primary font-bold text-2xl text-rose-600 dark:text-rose-400 mt-1">{{ $rejectedHki }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-300 flex items-center justify-center font-bold text-lg">
                ✕
            </div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden transition-colors duration-200">
        <div class="p-5 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <h3 class="font-primary font-bold text-sm text-gray-900 dark:text-white">Daftar Pengajuan HKI Dosen</h3>
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400">Total: {{ $hakiSubmissions->count() }} Permohonan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-800/80 border-b border-gray-200 dark:border-gray-800 text-[11px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        <th class="py-4 px-5 w-12 text-center">NO</th>
                        <th class="py-4 px-5">TANGGAL PENGAJUAN</th>
                        <th class="py-4 px-6">NAMA PENGUSUL &amp; JUDUL KARYA</th>
                        <th class="py-4 px-5">STATUS</th>
                        <th class="py-4 px-6 text-right">AKSI VERIFIKASI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-gray-800 dark:text-gray-200">
                    @forelse($hakiSubmissions as $item)
                        @php
                            $st = strtolower($item->status ?? 'diajukan');
                            $isApproved = in_array($st, ['disetujui', 'approved']);
                            $isRejected = in_array($st, ['ditolak', 'rejected']);
                            $isPending = !$isApproved && !$isRejected;
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-slate-800/40 transition-colors">
                            <!-- Nomor -->
                            <td class="py-4 px-5 text-center font-bold text-gray-400 dark:text-gray-500">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Tanggal Pengajuan -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="font-bold text-gray-900 dark:text-white">
                                    {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $item->created_at ? $item->created_at->format('H:i') : '' }} WIB
                                </div>
                            </td>

                            <!-- Nama Pengusul & Judul Karya -->
                            <td class="py-4 px-6 max-w-md">
                                <div class="font-extrabold text-sm text-gray-900 dark:text-white leading-snug">
                                    {{ $item->judul_ciptaan }}
                                </div>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        {{ $item->jenis_ciptaan ?? 'Hak Cipta' }}
                                    </span>
                                    @if($item->nomor_pencatatan)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        No: {{ $item->nomor_pencatatan }}
                                    </span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-gray-600 dark:text-gray-400 mt-1">
                                    Ketua: <span class="font-bold text-gray-800 dark:text-gray-200">{{ $item->nama_ketua }}</span>
                                    @if($item->nama_anggota)
                                    | Anggota: <span class="text-gray-700 dark:text-gray-300">{{ $item->nama_anggota }}</span>
                                    @endif
                                </div>
                                @if($item->catatan_admin)
                                <div class="mt-1.5 p-2 rounded-lg bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 text-[11px] text-gray-600 dark:text-gray-300">
                                    <span class="font-bold text-amber-700 dark:text-amber-400">Catatan:</span> {{ $item->catatan_admin }}
                                </div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                @if($isApproved)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-emerald-500"></span>
                                        Disetujui
                                    </span>
                                @elseif($isRejected)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-rose-500"></span>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-amber-500"></span>
                                        Pending / Menunggu
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <!-- a. Tombol "Cek Berkas" -->
                                    <button type="button" 
                                            @click="selectedHki = {
                                                id: '{{ $item->id }}',
                                                judul: '{{ addslashes($item->judul_ciptaan) }}',
                                                jenis: '{{ addslashes($item->jenis_ciptaan ?? 'Hak Cipta') }}',
                                                ketua: '{{ addslashes($item->nama_ketua) }}',
                                                anggota: '{{ addslashes($item->nama_anggota ?? '-') }}',
                                                status: '{{ addslashes($item->status ?? 'Diajukan') }}',
                                                file_pengalihan: '{{ $item->file_surat_pengalihan ? asset($item->file_surat_pengalihan) : '' }}',
                                                file_pernyataan: '{{ $item->file_surat_pernyataan ? asset($item->file_surat_pernyataan) : '' }}',
                                                file_karya: '{{ $item->file_lampiran_karya ? asset($item->file_lampiran_karya) : '' }}',
                                                file_ktp: '{{ $item->file_ktp ? asset($item->file_ktp) : '' }}',
                                                file_bayar: '{{ $item->file_bukti_pembayaran ? asset($item->file_bukti_pembayaran) : '' }}'
                                            }; modalBerkas = true;"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Cek Berkas</span>
                                    </button>

                                    <!-- b. Tombol "Setujui" (Warna Hijau) -->
                                    @if(!$isApproved)
                                    <form action="{{ route('admin.hki.approve', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan HKI: {{ addslashes($item->judul_ciptaan) }}?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Setujui</span>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- c. Tombol "Tolak" (Warna Merah) -->
                                    @if(!$isRejected)
                                    <button type="button" 
                                            @click="rejectId = '{{ $item->id }}'; rejectJudul = '{{ addslashes($item->judul_ciptaan) }}'; modalReject = true;"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Tolak</span>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="text-3xl mb-2">💡</div>
                                <div class="font-bold text-sm text-gray-800 dark:text-white">Belum Ada Pengajuan HKI Masuk</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pengajuan pencatatan ciptaan dari dosen akan tampil di meja kerja ini.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL CEK BERKAS HKI (ALPINE.JS) -->
    <div x-show="modalBerkas" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4"
         @keydown.escape.window="modalBerkas = false">
        
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-2xl max-w-xl w-full p-6 text-left relative transition-colors duration-200"
             @click.outside="modalBerkas = false">
            
            <div class="flex items-start justify-between pb-4 border-b border-gray-200 dark:border-gray-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800 inline-block mb-1"
                          x-text="selectedHki?.jenis"></span>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white leading-snug" x-text="selectedHki?.judul"></h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                        Ketua: <span class="font-semibold text-gray-800 dark:text-gray-200" x-text="selectedHki?.ketua"></span>
                    </p>
                </div>
                <button type="button" @click="modalBerkas = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- List File Dokumen Persyaratan -->
            <div class="py-4 space-y-3 text-xs">
                <span class="font-bold text-gray-900 dark:text-white block">Berkas Dokumen yang Diunggah Dosen:</span>

                <!-- 1. Surat Pengalihan Hak Cipta -->
                <div class="p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/60 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📄</span>
                        <div>
                            <div class="font-bold text-gray-900 dark:text-white">Surat Pengalihan Hak Cipta</div>
                            <div class="text-[10px] text-gray-500 dark:text-gray-400">Pengalihan ke LPPM Universitas Insan Mahardika</div>
                        </div>
                    </div>
                    <template x-if="selectedHki?.file_pengalihan">
                        <a :href="selectedHki?.file_pengalihan" target="_blank" download
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh File</span>
                        </a>
                    </template>
                    <template x-if="!selectedHki?.file_pengalihan">
                        <span class="text-[11px] text-gray-400 italic">Belum diunggah</span>
                    </template>
                </div>

                <!-- 2. Surat Pernyataan Hak Cipta -->
                <div class="p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/60 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📝</span>
                        <div>
                            <div class="font-bold text-gray-900 dark:text-white">Surat Pernyataan Keaslian Karya</div>
                            <div class="text-[10px] text-gray-500 dark:text-gray-400">Format resmi bermaterai Rp 10.000</div>
                        </div>
                    </div>
                    <template x-if="selectedHki?.file_pernyataan">
                        <a :href="selectedHki?.file_pernyataan" target="_blank" download
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh File</span>
                        </a>
                    </template>
                    <template x-if="!selectedHki?.file_pernyataan">
                        <span class="text-[11px] text-gray-400 italic">Belum diunggah</span>
                    </template>
                </div>

                <!-- 3. Draft Karya / Program -->
                <div class="p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/60 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">💾</span>
                        <div>
                            <div class="font-bold text-gray-900 dark:text-white">Draft Karya / Salinan Program</div>
                            <div class="text-[10px] text-gray-500 dark:text-gray-400">Naskah buku, modul, kode program ciptaan</div>
                        </div>
                    </div>
                    <template x-if="selectedHki?.file_karya">
                        <a :href="selectedHki?.file_karya" target="_blank" download
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh File</span>
                        </a>
                    </template>
                    <template x-if="!selectedHki?.file_karya">
                        <span class="text-[11px] text-gray-400 italic">Belum diunggah</span>
                    </template>
                </div>

                <!-- 4. KTP Asli Ketua & Anggota -->
                <template x-if="selectedHki?.file_ktp">
                    <div class="p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/60 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">🪪</span>
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white">KTP Ketua &amp; Anggota</div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400">Identitas kependudukan para pencipta</div>
                            </div>
                        </div>
                        <a :href="selectedHki?.file_ktp" target="_blank" download
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-700 hover:bg-gray-800 text-white font-bold text-xs transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh KTP</span>
                        </a>
                    </div>
                </template>

                <!-- 5. Bukti Pembayaran -->
                <template x-if="selectedHki?.file_bayar">
                    <div class="p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/60 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">💳</span>
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white">Bukti Pembayaran HKI</div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400">Struk transfer biaya pencatatan DJKI</div>
                            </div>
                        </div>
                        <a :href="selectedHki?.file_bayar" target="_blank" download
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Lihat Bukti</span>
                        </a>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center justify-end">
                <button type="button" @click="modalBerkas = false"
                        class="px-4 py-2 rounded-xl text-xs font-semibold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-200 transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- MODAL PENOLAKAN DENGAN CATATAN (ALPINE.JS) -->
    <div x-show="modalReject" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4"
         @keydown.escape.window="modalReject = false">
        
        <div class="bg-white dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-gray-800 shadow-2xl max-w-md w-full p-6 text-left relative transition-colors duration-200"
             @click.outside="modalReject = false">
            
            <div class="flex items-start justify-between pb-3 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                    <span class="text-xl">⚠️</span>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Tolak Pengajuan HKI</h3>
                </div>
                <button type="button" @click="modalReject = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="'{{ url('admin/hki') }}/' + rejectId + '/reject'" method="POST" class="py-4 space-y-4">
                @csrf
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    Anda akan menolak pengajuan karya: <strong class="text-gray-900 dark:text-white" x-text="rejectJudul"></strong>.
                </p>

                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">
                        Catatan Alasan Penolakan / Perbaikan *
                    </label>
                    <textarea name="catatan_admin" rows="3" required 
                              placeholder="Tuliskan catatan alasan penolakan atau berkas yang belum lengkap..." 
                              class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 dark:bg-slate-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-rose-500"></textarea>
                </div>

                <div class="pt-3 border-t border-gray-200 dark:border-gray-800 flex items-center justify-end gap-2">
                    <button type="button" @click="modalReject = false"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-200 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition shadow-xs cursor-pointer">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
