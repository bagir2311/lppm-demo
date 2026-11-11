@extends('layouts.app')

@section('title', 'Otomasi Persuratan Akademik LPPM Mahardika')

@section('content')
@php
    $isAdmin = session('admin_logged_in') 
        || (auth()->check() && in_array(auth()->user()->role, ['admin', 'admin_lppm'])) 
        || in_array(session('user_role'), ['admin', 'admin_lppm']);

    $currentUser = auth()->user();
    $currentDosen = $currentUser?->dosen;
    $isDosenUser = $currentUser && $currentUser->role === 'dosen';

    // Cari ID default dosen untuk dropdown jika user login adalah dosen
    $defaultSelectedDosenId = '';
    if ($currentDosen) {
        $defaultSelectedDosenId = (string) $currentDosen->id;
    } elseif ($isDosenUser) {
        $matched = isset($dosens) ? $dosens->firstWhere('name', $currentUser->name) : null;
        $defaultSelectedDosenId = $matched ? (string) $matched->id : ('user_' . $currentUser->id);
    }

    $defaultNama = $currentDosen?->nama ?? $currentUser?->name ?? '';
    $defaultNidn = $currentDosen?->nidn ?? $currentUser?->nidn ?? $currentUser?->identity_number ?? '-';
    $defaultProdi = $currentDosen?->prodi ?? 'Fakultas Ilmu Kesehatan';
@endphp
<div class="pt-28 pb-16 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ createModal: false, approveModal: false, selectedSurat: {} }">
        
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-200">
            <div class="space-y-1">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/40 text-[#00295f] dark:text-blue-300 text-xs font-bold uppercase tracking-wider">
                    <span>✉️ E-Surat Akademik</span>
                </div>
                <h1 class="font-primary font-bold text-2xl text-gray-900 dark:text-white">Otomasi Persuratan Penelitian &amp; Etik</h1>
                <p class="text-xs text-gray-700 dark:text-gray-300">Layanan mandiri penerbitan Surat Izin Penelitian Lapangan dan Surat Pengantar Komite Etik berbasis e-Signature QR Code.</p>
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
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors duration-200">
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
                            <th class="py-4 px-6">Keperluan &amp; Instansi Tujuan</th>
                            <th class="py-4 px-6">Tgl Pengajuan</th>
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
                                {{ $item->nama_dosen }}
                                <div class="text-[10px] text-gray-700 dark:text-gray-300 font-normal">NIDN: {{ $item->nidn }} | {{ $item->prodi }}</div>
                            </td>
                            <td class="py-4 px-6 max-w-xs">
                                <div class="line-clamp-2 font-semibold text-gray-900 dark:text-white">{{ $item->keperluan }}</div>
                                <div class="text-[10px] text-green-700 dark:text-green-400 font-medium mt-0.5">Tujuan: {{ $item->instansi_tujuan ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-gray-900 dark:text-gray-100">
                                {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($item->status === 'approved' || $item->status === 'Disetujui')
                                    <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Disetujui (Approved)</span>
                                @elseif($item->status === 'pending' || $item->status === 'Pending')
                                    <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Pending / Menunggu</span>
                                @else
                                    <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 font-medium px-2.5 py-0.5 rounded text-xs inline-block">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                @if($item->status === 'pending' || $item->status === 'Pending')
                                    @if($isAdmin)
                                        <button type="button" 
                                                @click="selectedSurat = {{ json_encode(['id' => $item->id, 'nama_dosen' => $item->nama_dosen, 'keperluan' => $item->keperluan, 'nomor_surat' => $item->nomor_surat ?: ($autoNomorSurat ?? '')], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}; approveModal = true" 
                                                class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-700 cursor-pointer transition">
                                            Setujui
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-yellow-50 text-yellow-800 border border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                            Menunggu Verifikasi Admin
                                        </span>
                                    @endif
                                @endif

                                @if($item->status === 'approved' || $item->status === 'Disetujui')
                                    <a href="{{ route('surat.cetak', $item->id) }}" target="_blank" 
                                       class="px-2.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer inline-flex items-center space-x-1 shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Cetak A4</span>
                                    </a>
                                    <a href="{{ route('surat.pdf', $item->id) }}" target="_blank" 
                                       class="px-2.5 py-1.5 bg-[#00295f] hover:bg-blue-900 text-white rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer inline-flex items-center space-x-1 shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z"/></svg>
                                        <span>PDF</span>
                                    </a>
                                @endif

                                @if($isAdmin)
                                    <form action="{{ route('surat.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-semibold hover:opacity-90 transition-opacity cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
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
        <div x-show="createModal" x-cloak 
             x-data="{ 
                jenis_surat: 'Surat Izin Penelitian',
                selected_dosen_id: '{{ $defaultSelectedDosenId }}',
                nama_dosen: @js($defaultNama),
                nidn: @js($defaultNidn ?: '-'),
                prodi: @js($defaultProdi),
                keperluan: '',
                instansi_tujuan: '',
                anggota: [],
                dosenList: @js($dosens),

                onDosenChange(event) {
                    const selectedId = event.target.value;
                    if (!selectedId) return;
                    const selected = this.dosenList.find(d => String(d.id) === String(selectedId));
                    if (selected) {
                        this.nama_dosen = selected.nama || selected.name || '';
                        this.nidn = selected.nidn || '-';
                        this.prodi = selected.prodi || '';
                    }
                },

                get instansiLabel() {
                    if (this.jenis_surat === 'Surat Pengantar Komite Etik Riset') {
                        return 'Komisi Etik / Lembaga KEPK Tujuan *';
                    } else if (this.jenis_surat === 'Surat Pengantar Studi Pendahuluan') {
                        return 'Instansi / Rumah Sakit / Lokasi Pendahuluan *';
                    } else if (this.jenis_surat === 'Surat Tugas Pengabdian Masyarakat') {
                        return 'Mitra / Lokasi Sasaran Pengabdian *';
                    }
                    return 'Instansi / Lokasi Tujuan *';
                },

                get instansiPlaceholder() {
                    if (this.jenis_surat === 'Surat Pengantar Komite Etik Riset') {
                        return 'Contoh: KEPK RSUD Gunung Jati Cirebon / Komite Etik Riset';
                    } else if (this.jenis_surat === 'Surat Pengantar Studi Pendahuluan') {
                        return 'Contoh: RS Permata Cirebon / RSUD Waled / Lab Klinik Prodia';
                    } else if (this.jenis_surat === 'Surat Tugas Pengabdian Masyarakat') {
                        return 'Contoh: Desa Sindangjawa Kec. Dukupuntang / Posyandu Melati';
                    }
                    return 'Contoh: Badan Kesbangpol Kab. Cirebon / Dinas Kesehatan';
                },

                get keperluanLabel() {
                    if (this.jenis_surat === 'Surat Pengantar Komite Etik Riset') {
                        return 'Judul Riset & Protokol Etik *';
                    } else if (this.jenis_surat === 'Surat Tugas Pengabdian Masyarakat') {
                        return 'Tema / Kegiatan Pengabdian Masyarakat *';
                    }
                    return 'Keperluan / Judul Penelitian *';
                },

                get keperluanPlaceholder() {
                    if (this.jenis_surat === 'Surat Pengantar Komite Etik Riset') {
                        return 'Masukkan judul riset yang diajukan kaji etik (Ethical Clearance)...';
                    } else if (this.jenis_surat === 'Surat Tugas Pengabdian Masyarakat') {
                        return 'Masukkan tema dan agenda pelaksanaan pengabdian...';
                    }
                    return 'Masukkan judul penelitian atau rincian keperluan surat secara jelas...';
                }
             }" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-xl w-full p-6 space-y-4 shadow-xl border border-gray-200 dark:border-gray-700 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                    <div>
                        <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Form Pengajuan Surat Baru</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Pilih jenis surat dan sesuaikan data pemohon secara dinamis</p>
                    </div>
                    <button type="button" @click="createModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">✕</button>
                </div>

                <form action="{{ route('surat.store') }}" method="POST" class="space-y-4" @submit="if(!nidn || !nidn.trim()) nidn = '-'">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Pilih Jenis Surat *</label>
                        <select name="jenis_surat" x-model="jenis_surat" required class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Surat Izin Penelitian">Surat Izin Penelitian (Kasbangpol / Lokasi Riset)</option>
                            <option value="Surat Pengantar Studi Pendahuluan">Surat Pengantar Studi Pendahuluan</option>
                            <option value="Surat Tugas Pengabdian Masyarakat">Surat Tugas Pengabdian Masyarakat</option>
                            <option value="Surat Pengantar Komite Etik Riset">Surat Pengantar Komite Etik Riset (Ethical Clearance)</option>
                        </select>
                    </div>

                    @if(isset($dosens) && $dosens->isNotEmpty())
                    <div>
                        <label for="dosen_id" class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Pilih Data Dosen Terdaftar (Otomatis)</label>
                        <select id="dosen_id" name="dosen_id" x-model="selected_dosen_id" x-on:change="onDosenChange($event)" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Isi Manual atau Pilih Profil Dosen --</option>
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ (string)$d->id === (string)$defaultSelectedDosenId ? 'selected' : '' }}>
                                    {{ $d->nama ?? $d->name }} (NIDN: {{ $d->nidn ?? '-' }} - {{ $d->prodi ?? 'Dosen' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">Memilih dosen di atas akan mengisi otomatis Nama, NIDN, dan Program Studi di bawah ini.</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Nama Lengkap &amp; Gelar Dosen *</label>
                            <input type="text" name="nama_dosen" x-model="nama_dosen" required placeholder="Dr. Apt. Rina Kusuma, M.Farm." class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">NIDN / NIP (Opsional)</label>
                            <input type="text" name="nidn" x-model="nidn" placeholder="0412088501 atau isi tanda strip (-)" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1">Program Studi / Fakultas *</label>
                        <input type="text" name="prodi" x-model="prodi" required placeholder="Farmasi / Fakultas Ilmu Kesehatan" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1" x-text="keperluanLabel"></label>
                        <textarea name="keperluan" x-model="keperluan" rows="3" required :placeholder="keperluanPlaceholder" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1" x-text="instansiLabel"></label>
                        <input type="text" name="instansi_tujuan" x-model="instansi_tujuan" required :placeholder="instansiPlaceholder" class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1" x-show="jenis_surat === 'Surat Pengantar Komite Etik Riset'">
                            ℹ️ Masukkan nama Lembaga / Komisi Etik Penelitian Kesehatan (KEPK), misal: KEPK RSUD Gunung Jati.
                        </p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1" x-show="jenis_surat === 'Surat Pengantar Studi Pendahuluan'">
                            ℹ️ Masukkan nama Rumah Sakit / Puskesmas / Instansi tempat pengambilan data awal, misal: RS Permata Cirebon.
                        </p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1" x-show="jenis_surat === 'Surat Izin Penelitian'">
                            ℹ️ Masukkan instansi perizinan tujuan (misal: Bakesbangpol Kab. Cirebon atau lokasi penelitian).
                        </p>
                    </div>

                    <!-- BLOK ANGGOTA TIM DINAMIS (Khusus Surat Izin Penelitian) -->
                    <div x-show="jenis_surat === 'Surat Izin Penelitian'" x-transition class="p-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl space-y-3 mt-4 text-left">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-xs font-bold text-gray-900 dark:text-white">Anggota Tim Peneliti (Opsional / Kolektif)</label>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400">Khusus surat izin rombongan/kolektif seperti ke Badan Kesbangpol</p>
                            </div>
                            <button type="button" @click="anggota.push({ nama: '', judul: '' })" class="px-2.5 py-1 text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 rounded-lg hover:bg-blue-200 transition-colors">
                                + Tambah Anggota
                            </button>
                        </div>
                        
                        <div class="space-y-2">
                            <template x-for="(item, index) in anggota" :key="index">
                                <div class="p-2 border border-gray-200 dark:border-gray-600 rounded-lg space-y-2 bg-white dark:bg-gray-800">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400" x-text="'Anggota #' + (index + 1)"></span>
                                        <button type="button" @click="anggota.splice(index, 1)" class="text-xs text-red-500 hover:text-red-700 font-bold">
                                            ✕ Hapus
                                        </button>
                                    </div>
                                    <input type="text" name="anggota_nama[]" x-model="item.nama" placeholder="Nama Lengkap Anggota..." class="w-full px-3 py-1.5 border rounded-lg text-xs bg-white text-gray-900 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <input type="text" name="anggota_judul[]" x-model="item.judul" placeholder="Judul Penelitian Anggota (Jika beda judul)..." class="w-full px-3 py-1.5 border rounded-lg text-xs bg-white text-gray-900 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                            </template>
                        </div>
                        <p x-show="anggota.length === 0" class="text-[11px] text-gray-400 italic">Klik "+ Tambah Anggota" jika pengajuan surat izin dilakukan secara rombongan/kolektif.</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="createModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-[#00295f] hover:bg-blue-900 text-white text-xs font-bold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Kirim Pengajuan Surat</button>
                    </div>
                </form>
            </div>
        </div>

        @if($isAdmin)
        <!-- Modal Setujui Surat (Admin) -->
        <div x-show="approveModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h3 class="font-primary font-bold text-base text-gray-900 dark:text-white">Persetujuan &amp; Penerbitan Surat</h3>
                    <button type="button" @click="approveModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer">✕</button>
                </div>

                <form :action="'{{ url('surat/approve') }}/' + selectedSurat.id" method="POST" class="space-y-4">
                    @csrf
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-xs text-blue-900 dark:text-blue-200 font-medium">
                        Pemohon: <span x-text="selectedSurat.nama_dosen" class="font-bold"></span><br>
                        Keperluan: <span x-text="selectedSurat.keperluan" class="italic"></span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-gray-900 dark:text-white">
                                Nomor Surat Resmi
                            </label>
                            <span class="text-[10px] text-emerald-700 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-300 px-2 py-0.5 rounded-full font-bold">
                                Otomatis Terisi
                            </span>
                        </div>
                        <input type="text" 
                               name="nomor_surat" 
                               x-model="selectedSurat.nomor_surat"
                               placeholder="Contoh: 0019/LPPM UIM/X/2026" 
                               class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                            Format Resmi: [No. Urut 4 Digit]/LPPM UIM/[Bulan Romawi]/[Tahun]
                        </p>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Sistem otomatis menghasilkan <strong>QR Code e-Signature</strong> bertanda tangan digital Kepala LPPM berdasar hash MD5 terverifikasi.
                    </p>

                    <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="approveModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white text-xs font-bold rounded-xl hover:opacity-90 transition-opacity cursor-pointer">Setujui &amp; Terbitkan Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
