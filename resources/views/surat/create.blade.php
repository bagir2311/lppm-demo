@extends('layouts.app')

@section('title', 'Form Pengajuan Surat Akademik - LPPM Mahardika')

@section('content')
@php
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
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Back Button -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('surat.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#00295f] dark:text-blue-400 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Daftar Persuratan</span>
            </a>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/40 text-[#00295f] dark:text-blue-300 text-xs font-bold">
                <span>✉️ Layanan E-Surat Resmi</span>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 sm:p-8"
             x-data="{ 
                jenis_surat: 'Permohonan Ijin Penelitian',
                selected_dosen_id: '{{ $defaultSelectedDosenId }}',
                nama_dosen: @js($defaultNama),
                nidn: @js($defaultNidn ?: '-'),
                prodi: @js($defaultProdi),
                keperluan: '',
                instansi_tujuan: '',
                lokasi_penelitian: '',
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

                get isIjinPenelitian() {
                    const j = (this.jenis_surat || '').toLowerCase();
                    return j.includes('ijin penelitian') || j.includes('izin penelitian');
                },

                get isStudiPendahuluan() {
                    const j = (this.jenis_surat || '').toLowerCase();
                    return j.includes('studi pendahuluan') || j.includes('pendahuluan');
                },

                get instansiLabel() {
                    if (this.isStudiPendahuluan) {
                        return 'Kepada Yth (Pimpinan / Instansi Tujuan) *';
                    } else if (this.isIjinPenelitian) {
                        return 'Kepada Yth (Instansi / Pejabat Tujuan Perizinan) *';
                    } else if (this.jenis_surat === 'Surat Pengantar Komite Etik Riset') {
                        return 'Komisi Etik / Lembaga KEPK Tujuan *';
                    }
                    return 'Kepada Yth (Instansi Tujuan) *';
                },

                get instansiPlaceholder() {
                    if (this.isStudiPendahuluan) {
                        return 'Contoh: Direktur RS Permata';
                    } else if (this.isIjinPenelitian) {
                        return 'Contoh: Kasbangpol Kota Cirebon atau Direktur RSD Gunung Jati Kota Cirebon';
                    } else if (this.jenis_surat === 'Surat Pengantar Komite Etik Riset') {
                        return 'Contoh: KEPK RSUD Gunung Jati Cirebon';
                    }
                    return 'Contoh: Kasbangpol Kota Cirebon';
                },

                get lokasiLabel() {
                    return 'Tempat / Lokasi Pelaksanaan Penelitian *';
                },

                get lokasiPlaceholder() {
                    if (this.isStudiPendahuluan) {
                        return 'Contoh: RS Permata';
                    }
                    return 'Contoh: RSD Gunung Jati Kota Cirebon';
                },

                get keperluanLabel() {
                    if (this.isIjinPenelitian || this.isStudiPendahuluan) {
                        return 'Judul Penelitian *';
                    } else if (this.jenis_surat === 'Surat Tugas Pengabdian Masyarakat') {
                        return 'Tema / Agenda Pengabdian Masyarakat *';
                    }
                    return 'Judul Riset / Keperluan Surat *';
                },

                get keperluanPlaceholder() {
                    if (this.isIjinPenelitian || this.isStudiPendahuluan) {
                        return 'Contoh: Pengembangan Aplikasi SAHATI Sebagai Alat Digital Kesejahteraan Psikologi Pasien Paliatif';
                    }
                    return 'Masukkan rincian judul atau keperluan surat...';
                }
             }">

            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                <h1 class="font-primary font-bold text-xl text-gray-900 dark:text-white">Formulir Pengajuan Surat Akademik</h1>
                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Sesuaikan jenis surat dan lengkapi rincian data peneliti serta instansi tujuan.</p>
            </div>

            <form action="{{ route('surat.store') }}" method="POST" class="space-y-5" @submit="if(!nidn || !nidn.trim()) nidn = '-'">
                @csrf

                <!-- Pilih Jenis Surat -->
                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1.5">Pilih Jenis Surat Resmi *</label>
                    <select name="jenis_surat" x-model="jenis_surat" required class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white font-medium">
                        <option value="Permohonan Ijin Penelitian">Permohonan Ijin Penelitian (Rektor / Kasbangpol / RS / Lokasi Riset)</option>
                        <option value="Permohonan Ijin Studi Pendahuluan">Permohonan Ijin Studi Pendahuluan (an. Rektor Wakil Rektor I)</option>
                        <option value="Surat Tugas Pengabdian Masyarakat">Surat Tugas Pengabdian Masyarakat</option>
                        <option value="Surat Pengantar Komite Etik Riset">Surat Pengantar Komite Etik Riset (Ethical Clearance)</option>
                    </select>
                </div>

                <!-- Dropdown Pilih Dosen Terdaftar Otomatis -->
                @if(isset($dosens) && $dosens->isNotEmpty())
                <div>
                    <label for="dosen_id" class="block text-xs font-bold text-gray-900 dark:text-white mb-1.5">Pilih Data Dosen Pemohon (Otomatis)</label>
                    <select id="dosen_id" name="dosen_id" x-model="selected_dosen_id" x-on:change="onDosenChange($event)" class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Isi Manual atau Pilih Profil Dosen --</option>
                        @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ (string)$d->id === (string)$defaultSelectedDosenId ? 'selected' : '' }}>
                                {{ $d->nama ?? $d->name }} (NIDN: {{ $d->nidn ?? '-' }} - {{ $d->prodi ?? 'Dosen' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">Memilih dosen di atas akan mengisi otomatis Nama, NIDN, dan Program Studi.</p>
                </div>
                @endif

                <!-- Data Dosen Pemohon -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1.5">Nama Dosen (Ketua Peneliti) *</label>
                        <input type="text" name="nama_dosen" x-model="nama_dosen" required placeholder="Contoh: Ns. Endah Sari Purbaningsih, M. Kep" class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1.5">NIDN / NIP (Opsional)</label>
                        <input type="text" name="nidn" x-model="nidn" placeholder="0412088501 atau isi tanda strip (-)" class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1.5">Program Studi / Fakultas</label>
                    <input type="text" name="prodi" x-model="prodi" placeholder="Contoh: Keperawatan / Fakultas Ilmu Kesehatan" class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Instansi Tujuan Surat (Kepada Yth) -->
                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1.5" x-text="instansiLabel"></label>
                    <input type="text" name="instansi_tujuan" x-model="instansi_tujuan" required :placeholder="instansiPlaceholder" class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1" x-show="isIjinPenelitian">
                        ℹ️ Masukkan nama instansi/badan yang dituju pada bagian "Kepada Yth:", contoh: <strong>Kasbangpol Kota Cirebon</strong> atau <strong>Direktur RSD Gunung Jati Kota Cirebon</strong>.
                    </p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1" x-show="isStudiPendahuluan">
                        ℹ️ Masukkan nama pimpinan & instansi tujuan, contoh: <strong>Direktur RS Permata</strong>.
                    </p>
                </div>

                <!-- Tempat / Lokasi Pelaksanaan Penelitian -->
                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1.5" x-text="lokasiLabel"></label>
                    <input type="text" name="lokasi_penelitian" x-model="lokasi_penelitian" :placeholder="lokasiPlaceholder" class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">
                        Tempat/lokasi dilaksanakannya penelitian (contoh kalimat surat: <em>"diberi ijin melaksanakan Penelitian di RSD Gunung Jati Kota Cirebon"</em>). Jika sama dengan Instansi Tujuan di atas, boleh dikosongkan.
                    </p>
                </div>

                <!-- Judul Penelitian -->
                <div>
                    <label class="block text-xs font-bold text-gray-900 dark:text-white mb-1.5" x-text="keperluanLabel"></label>
                    <textarea name="keperluan" x-model="keperluan" rows="3" required :placeholder="keperluanPlaceholder" class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-[#00295f] focus:border-[#00295f] dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>

                <!-- TABEL TIM PENELITI (Khusus Permohonan Ijin Penelitian) -->
                <div x-show="isIjinPenelitian" x-transition class="p-4 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl space-y-3 mt-4 text-left">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-xs font-bold text-gray-900 dark:text-white">Tabel Peneliti (Ketua &amp; Anggota Tim)</label>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400">Peneliti No. 1 otomatis diisi dari Dosen Pemohon di atas. Klik "+ Tambah Anggota" untuk menambahkan anggota tim peneliti lainnya.</p>
                        </div>
                        <button type="button" @click="anggota.push({ nama: '', judul: '' })" class="px-3 py-1.5 text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 rounded-lg hover:bg-blue-200 transition-colors">
                            + Tambah Anggota
                        </button>
                    </div>

                    <!-- Pratinjau Baris 1: Ketua -->
                    <div class="p-3 border border-blue-200 dark:border-blue-800 bg-blue-50/50 dark:bg-blue-950/20 rounded-xl space-y-1 text-xs">
                        <div class="flex items-center justify-between text-blue-900 dark:text-blue-300 font-bold">
                            <span>No. 1 (Ketua Peneliti)</span>
                            <span class="text-[10px] uppercase bg-blue-200 dark:bg-blue-900 px-2 py-0.5 rounded">Otomatis</span>
                        </div>
                        <div class="text-gray-800 dark:text-gray-200 font-semibold" x-text="nama_dosen || '(Nama Dosen Pemohon)'"></div>
                        <div class="text-gray-600 dark:text-gray-400 text-[11px]" x-text="keperluan || '(Judul Penelitian)'"></div>
                    </div>
                    
                    <!-- Baris Anggota Tambahan (No 2, 3, dst) -->
                    <div class="space-y-3">
                        <template x-for="(item, index) in anggota" :key="index">
                            <div class="p-3 border border-gray-200 dark:border-gray-600 rounded-xl space-y-2 bg-white dark:bg-gray-800">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300" x-text="'No. ' + (index + 2) + ' (Anggota Tim)'"></span>
                                    <button type="button" @click="anggota.splice(index, 1)" class="text-xs text-red-500 hover:text-red-700 font-bold">
                                        ✕ Hapus
                                    </button>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 mb-1">Nama Lengkap &amp; Gelar</label>
                                    <input type="text" name="anggota_nama[]" x-model="item.nama" required placeholder="Contoh: Ns. Muadi, M. Kep" class="w-full px-3 py-2 border rounded-lg text-xs bg-white text-gray-900 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 mb-1">Judul Penelitian (Boleh dikosongkan jika satu judul penelitian yang sama)</label>
                                    <input type="text" name="anggota_judul[]" x-model="item.judul" placeholder="Kosongkan jika judul sama dengan Ketua..." class="w-full px-3 py-2 border rounded-lg text-xs bg-white text-gray-900 border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('surat.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl transition">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00295f] hover:bg-blue-900 text-white text-xs font-bold rounded-xl transition-colors shadow-sm">Kirim Pengajuan Surat</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
