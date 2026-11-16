@extends('layouts.app')

@section('title', 'Pendaftaran Pengajuan HKI - Sentra HKI LPPM Mahardika')

@section('content')
<div class="pt-28 pb-16 bg-gray-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Navigasi Kembali & Breadcrumb -->
        <div class="flex items-center justify-between">
            <a href="{{ route('dosen.dashboard', ['tab' => 'haki']) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Dasbor Dosen (Tab HKI)</span>
            </a>
            <span class="text-[11px] font-mono text-gray-400 dark:text-gray-500">Sentra HKI Mahardika v2.0</span>
        </div>

        <!-- Header Card Info Sentra HKI -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 shadow-sm transition-colors duration-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 dark:border-gray-700 pb-5">
                <div class="space-y-1">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 text-xs font-bold uppercase tracking-wider">
                        <span>💡 Sentra Hak Kekayaan Intelektual (HKI)</span>
                    </div>
                    <h1 class="font-primary font-bold text-2xl text-gray-900 dark:text-white">Formulir Pengajuan Pencatatan HKI</h1>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Pendaftaran hak cipta dan paten resmi DJKI Kemenkumham RI melalui LPPM Universitas Insan Mahardika.</p>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl text-xs font-semibold text-amber-900 dark:text-amber-200 shrink-0 sm:text-right">
                    <span>Koordinator Sentra HKI</span>
                    <div class="font-bold text-sm text-gray-900 dark:text-white mt-0.5">{{ $coordinatorName ?? ($settings['haki_coordinator_name'] ?? 'Ibu Iffah') }}</div>
                    @php
                        $contact = $coordinatorContact ?? ($settings['haki_coordinator_contact'] ?? null);
                    @endphp
                    @if(!empty($contact))
                    <div class="text-[11px] text-amber-800 dark:text-amber-300 font-normal mt-0.5">{{ $contact }}</div>
                    @endif
                </div>
            </div>

            <!-- b) SEKSI TEMPLATE RESMI -->
            <div class="mt-5 p-4 rounded-xl bg-amber-50/80 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                        📄
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">Unduh Template Berkas Resmi Sentra HKI</div>
                        <div class="text-[11px] text-gray-600 dark:text-gray-300">Harap unduh dan lengkapi 3 template format resmi berikut sebelum mengunggah berkas usulan Anda:</div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1">
                    <a href="{{ route('dosen.haki.template', 'data_pembuat') }}" 
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-amber-300 dark:border-amber-700 text-xs font-bold text-amber-800 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-slate-700 transition shadow-xs text-center">
                        <span>📥 Template Data Pembuat Karya</span>
                    </a>
                    <a href="{{ route('dosen.haki.template', 'pengalihan') }}" 
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-amber-300 dark:border-amber-700 text-xs font-bold text-amber-800 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-slate-700 transition shadow-xs text-center">
                        <span>📥 Template Surat Pengalihan</span>
                    </a>
                    <a href="{{ route('dosen.haki.template', 'pernyataan') }}" 
                       class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-amber-300 dark:border-amber-700 text-xs font-bold text-amber-800 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-slate-700 transition shadow-xs text-center">
                        <span>📥 Template Surat Pernyataan</span>
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 dark:bg-red-950/40 dark:border-red-800 dark:text-red-200 text-xs space-y-1">
            <p class="font-bold">Periksa kembali data formulir yang belum lengkap:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Formulir Pendaftaran HKI (Alpine.js State) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 shadow-sm transition-colors duration-200"
             x-data="{ 
                 pencipta: [
                     {
                         nama: '{{ old('pencipta.0.nama', $dosen->nama ?? auth()->user()->name ?? '') }}', 
                         nik: '{{ old('pencipta.0.nik', '') }}', 
                         alamat: '{{ old('pencipta.0.alamat', '') }}', 
                         kodepos: '{{ old('pencipta.0.kodepos', '') }}', 
                         email: '{{ old('pencipta.0.email', auth()->user()->email ?? '') }}', 
                         nohp: '{{ old('pencipta.0.nohp', '') }}'
                     }
                 ] 
             }">

            <form action="{{ route('dosen.haki.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="dosen_id" value="{{ $dosen->id ?? '' }}">

                <!-- a) SEKSI INFO KARYA -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 flex items-center justify-center font-bold text-xs">1</span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                            Seksi Informasi Karya / Ciptaan
                        </h3>
                    </div>

                    <div>
                        <label for="judul_ciptaan" class="block text-xs font-bold text-gray-900 dark:text-white mb-1">
                            Judul Karya / Ciptaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul_ciptaan" name="judul_ciptaan" required value="{{ old('judul_ciptaan') }}" 
                               placeholder="Contoh: Modul Pembelajaran Interaktif Farmakologi Berbasis Mobile" 
                               class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white placeholder-gray-400 transition">
                    </div>

                    <div>
                        <label for="jenis_ciptaan" class="block text-xs font-bold text-gray-900 dark:text-white mb-1">
                            Jenis Ciptaan <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_ciptaan" name="jenis_ciptaan" required 
                                class="w-full px-3.5 py-2.5 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                            <option value="">-- Pilih Jenis Ciptaan --</option>
                            <option value="Buku" {{ old('jenis_ciptaan') == 'Buku' ? 'selected' : '' }}>📚 Buku / Buku Referensi / Buku Ajar</option>
                            <option value="Program Komputer" {{ old('jenis_ciptaan') == 'Program Komputer' ? 'selected' : '' }}>💻 Program Komputer / Aplikasi Web &amp; Mobile</option>
                            <option value="Modul" {{ old('jenis_ciptaan') == 'Modul' ? 'selected' : '' }}>📑 Modul / Diktat Pembelajaran</option>
                            <option value="Karya Tulis Ilmiah" {{ old('jenis_ciptaan') == 'Karya Tulis Ilmiah' ? 'selected' : '' }}>📝 Karya Tulis Ilmiah / Monograf</option>
                            <option value="Karya Sinematografi" {{ old('jenis_ciptaan') == 'Karya Sinematografi' ? 'selected' : '' }}>🎬 Karya Sinematografi / Video Edukasi</option>
                            <option value="Desain Poster / Flyer" {{ old('jenis_ciptaan') == 'Desain Poster / Flyer' ? 'selected' : '' }}>🎨 Desain Poster / Flyer / Infografis</option>
                            <option value="Paten / Paten Sederhana" {{ old('jenis_ciptaan') == 'Paten / Paten Sederhana' ? 'selected' : '' }}>⚙️ Paten / Paten Sederhana</option>
                            <option value="Lainnya" {{ old('jenis_ciptaan') == 'Lainnya' ? 'selected' : '' }}>✨ Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- c) SEKSI DATA PENCIPTA (ALPINE.JS DINAMIS) -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 flex items-center justify-center font-bold text-xs">2</span>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                Seksi Data Pencipta / Pembuat Karya
                            </h3>
                        </div>
                        <button type="button" 
                                @click="pencipta.push({ nama:'', nik:'', alamat:'', kodepos:'', email:'', nohp:'' })" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-xs font-bold hover:bg-blue-100 dark:hover:bg-blue-900/60 transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Tambah Pencipta</span>
                        </button>
                    </div>

                    <p class="text-[11px] text-gray-500 dark:text-gray-400">
                        Pencipta #1 secara otomatis dianggap sebagai <strong>Ketua Pengusul</strong>. Tambahkan anggota lain sesuai kebutuhan tim.
                    </p>

                    <div class="space-y-4">
                        <template x-for="(item, index) in pencipta" :key="index">
                            <div class="p-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-slate-700/40 space-y-3 relative transition">
                                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <span class="text-xs font-extrabold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-md bg-amber-500 text-white flex items-center justify-center text-[10px]" x-text="index + 1"></span>
                                        <span x-text="index === 0 ? 'Pencipta 1 (Ketua Pengusul)' : 'Pencipta ' + (index + 1) + ' (Anggota)'"></span>
                                    </span>
                                    <template x-if="pencipta.length > 1">
                                        <button type="button" @click="pencipta.splice(index, 1)" class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 font-bold flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>✕ Hapus</span>
                                        </button>
                                    </template>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1">
                                            Nama Lengkap (beserta Gelar) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'pencipta[' + index + '][nama]'" x-model="item.nama" required 
                                               placeholder="Contoh: Dr. Apt. Rina Kusuma, M.Farm." 
                                               class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1">
                                            NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'pencipta[' + index + '][nik]'" x-model="item.nik" required 
                                               placeholder="16 Digit NIK sesuai KTP..." 
                                               class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1">
                                            Alamat Lengkap Sesuai KTP <span class="text-red-500">*</span>
                                        </label>
                                        <textarea :name="'pencipta[' + index + '][alamat]'" x-model="item.alamat" rows="2" required 
                                                  placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi..." 
                                                  class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white transition"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1">
                                            Kode Pos <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'pencipta[' + index + '][kodepos]'" x-model="item.kodepos" required 
                                               placeholder="Contoh: 45153" 
                                               class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1">
                                            Alamat Email Aktif <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" :name="'pencipta[' + index + '][email]'" x-model="item.email" required 
                                               placeholder="email@mahardika.ac.id" 
                                               class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-[11px] font-bold text-gray-700 dark:text-gray-300 mb-1">
                                            Nomor Handphone / WhatsApp <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'pencipta[' + index + '][nohp]'" x-model="item.nohp" required 
                                               placeholder="Contoh: 081234567890" 
                                               class="w-full px-3 py-2 border rounded-xl text-xs bg-white text-gray-900 border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:border-gray-600 dark:text-white transition">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- d) SEKSI UPLOAD BERKAS -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-bold text-xs">3</span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                            Seksi Upload Berkas Persyaratan
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Input File 1: Upload Surat Pengalihan Hak Cipta -->
                        <div class="p-4 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-slate-700/40 space-y-2">
                            <label class="block text-xs font-bold text-gray-900 dark:text-white">
                                1. Surat Pengalihan Hak Cipta <span class="text-red-500">*</span>
                            </label>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">
                                Berkas pengalihan hak kepada LPPM Mahardika (Format: PDF, DOC, DOCX, ZIP, Maks 20 MB).
                            </span>
                            <input type="file" name="file_surat_pengalihan" required accept=".pdf,.doc,.docx,.zip" 
                                   class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer cursor-pointer">
                        </div>

                        <!-- Input File 2: Upload Surat Pernyataan Hak Cipta (Bermaterai) -->
                        <div class="p-4 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-slate-700/40 space-y-2">
                            <label class="block text-xs font-bold text-gray-900 dark:text-white">
                                2. Surat Pernyataan Hak Cipta (Bermaterai) <span class="text-red-500">*</span>
                            </label>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">
                                Surat keaslian karya bermaterai &amp; bertanda tangan (Format: PDF, DOC, DOCX, ZIP, Maks 20 MB).
                            </span>
                            <input type="file" name="file_surat_pernyataan" required accept=".pdf,.doc,.docx,.zip" 
                                   class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer cursor-pointer">
                        </div>
                    </div>

                    <!-- Input File 3: Upload Draft Karya / Program -->
                    <div class="p-4 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-slate-700/40 space-y-2">
                        <label class="block text-xs font-bold text-gray-900 dark:text-white">
                            3. Draft Karya / Salinan Program Komputer <span class="text-red-500">*</span>
                        </label>
                        <span class="block text-[10px] text-gray-500 dark:text-gray-400">
                            Naskah buku, file modul, source code program, manual book, atau karya cipta (Format: PDF, DOC, DOCX, ZIP, RAR, Maks 30 MB).
                        </span>
                        <input type="file" name="file_draft_karya" required accept=".pdf,.doc,.docx,.zip,.rar" 
                               class="w-full text-xs text-gray-700 dark:text-gray-200 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer cursor-pointer">
                    </div>
                </div>

                <!-- Tombol Submit & Aksi -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">
                        Pastikan seluruh data pencipta dan dokumen yang Anda unggah sudah sesuai ketentuan Sentra HKI LPPM.
                    </p>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('dosen.dashboard', ['tab' => 'haki']) }}" 
                           class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-sm transition inline-flex items-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Kirim Pengajuan HKI</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
