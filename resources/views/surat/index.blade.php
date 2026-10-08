@extends('layouts.app')

@section('title', 'Otomasi Persuratan Akademik LPPM Mahardika')

@section('content')
<div class="pt-28 pb-16 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ createModal: false, approveModal: false, selectedSurat: {} }">
        
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="space-y-1">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/40 text-[#00295f] dark:text-blue-300 text-xs font-bold uppercase tracking-wider">
                    <span>✉️ E-Surat Akademik</span>
                </div>
                <h1 class="font-primary font-bold text-2xl text-gray-900 dark:text-white">Otomasi Persuratan Penelitian &amp; Etik</h1>
                <p class="text-xs text-gray-700 dark:text-gray-300">Layanan mandiri penerbitan Surat Izin Penelitian Lapangan dan Surat Pengantar Komite Etik.</p>
            </div>

            <button type="button" @click="createModal = true" class="px-5 py-3 bg-[#00295f] hover:bg-blue-900 text-white rounded-xl text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm flex items-center space-x-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Ajukan Surat Baru</span>
            </button>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/40 dark:border-green-800 dark:text-green-200 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button type="button" @click="$el.parentElement.remove()" class="text-green-700 hover:text-green-900 dark:text-green-300 cursor-pointer">✕</button>
        </div>
        @endif

        <!-- Surat List Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Daftar Pengajuan Surat Akademik</h3>
                <span class="text-xs text-gray-700 dark:text-gray-300 font-semibold">Total: {{ $surats->count() }} Pengajuan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold uppercase text-xs border-b border-gray-200 dark:border-gray-700">
                            <th class="py-4 px-6">Jenis &amp; No. Surat</th>
                            <th class="py-4 px-6">Dosen Pemohon</th>
                            <th class="py-4 px-6">Judul &amp; Lokasi Riset</th>
                            <th class="py-4 px-6">Tgl Pelaksanaan</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        @forelse($surats as $item)
                        <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-50/70 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-900/40 text-[#00295f] dark:text-blue-300">
                                    {{ $item->jenis_surat }}
                                </span>
                                <div class="font-mono text-[11px] text-gray-900 dark:text-white font-bold mt-1">
                                    {{ $item->nomor_surat ?? 'Belum diterbitkan' }}
                                </div>
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                {{ $item->dosen->nama ?? 'Dosen Insan Mahardika' }}
                                <div class="text-[10px] text-gray-700 dark:text-gray-300 font-normal">{{ $item->dosen->prodi ?? 'Fakultas Kesehatan' }}</div>
                            </td>
                            <td class="py-4 px-6 max-w-xs">
                                <div class="line-clamp-2 font-semibold text-gray-900 dark:text-white">{{ $item->judul_penelitian }}</div>
                                <div class="text-[10px] text-green-700 dark:text-green-400 font-medium mt-0.5">Lokasi: {{ $item->lokasi_penelitian ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-gray-900 dark:text-gray-100">
                                {{ $item->tanggal_mulai ? date('d M Y', strtotime($item->tanggal_mulai)) : '-' }} s/d <br>
                                {{ $item->tanggal_selesai ? date('d M Y', strtotime($item->tanggal_selesai)) : '-' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($item->status === 'Disetujui')
                                    <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Disetujui</span>
                                @elseif($item->status === 'Pending')
                                    <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Pending / Proses</span>
                                @else
                                    <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                @if($item->status === 'Pending')
                                    <button type="button" @click="selectedSurat = {{ json_encode($item) }}; approveModal = true" 
                                            class="px-3 py-1.5 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer">
                                        Setujui &amp; Verifikasi
                                    </button>
                                @endif

                                @if($item->status === 'Disetujui')
                                    <a href="{{ route('surat.pdf', $item->id) }}" target="_blank" 
                                       class="px-3 py-1.5 bg-[#00295f] hover:bg-blue-900 text-white rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer inline-flex items-center space-x-1 shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Cetak Surat PDF</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-700 dark:text-gray-300">Belum ada pengajuan surat terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Buat Surat Baru -->
        <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-xl w-full p-6 space-y-4 shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Form Pengajuan Surat Resmi</h3>
                    <button type="button" @click="createModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">✕</button>
                </div>

                <form action="{{ route('surat.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Pilih Jenis Surat *</label>
                        <select name="jenis_surat" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Surat Izin Penelitian" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Surat Izin Penelitian Lapangan</option>
                            <option value="Pengantar Etik Penelitian" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">Surat Pengantar Komite Etik Penelitian (Ethical Clearance)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Dosen Pemohon *</label>
                        <select name="dosen_id" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}" class="text-gray-900 dark:text-white bg-white dark:bg-gray-700">{{ $d->nama }} ({{ $d->prodi }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Judul Riset / Pengabdian *</label>
                        <textarea name="judul_penelitian" rows="2" required placeholder="Masukkan judul riset lengkap..." class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Lokasi Target Penelitian / Mitra *</label>
                        <input type="text" name="lokasi_penelitian" placeholder="Contoh: RSUD Waled / Puskesmas Sumber" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Tanggal Mulai *</label>
                            <input type="date" name="tanggal_mulai" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Tanggal Selesai *</label>
                            <input type="date" name="tanggal_selesai" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="createModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-[#00295f] hover:bg-blue-900 text-white text-xs font-bold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Kirim Pengajuan Surat</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Setujui Surat (Admin) -->
        <div x-show="approveModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Verifikasi &amp; Terbitkan Surat</h3>
                    <button type="button" @click="approveModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">✕</button>
                </div>

                <form :action="'{{ url('surat/approve') }}/' + selectedSurat.id" method="POST" class="space-y-4">
                    @csrf
                    <p class="text-xs text-gray-700 dark:text-gray-300">
                        Sistem akan otomatis menghasilkan Nomor Surat Resmi LPPM dan menyematkan Stempel &amp; Tanda Tangan Digital LPPM Mahardika.
                    </p>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Catatan Verifikator LPPM</label>
                        <textarea name="catatan_admin" rows="3" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Syarat administrasi dinyatakan lengkap."></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="approveModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white text-xs font-bold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Setujui &amp; Terbitkan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
