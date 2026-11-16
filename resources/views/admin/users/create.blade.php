@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Tambah Akun Pengguna & Staf Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="{ role: '{{ old('role', 'dosen') }}' }">

    <!-- Navigasi Kembali -->
    <div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-text-secondary hover:text-mahardika-navy dark:text-dark-text-muted dark:hover:text-navy-accent transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Manajemen Pengguna</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-border-main rounded-3xl p-6 sm:p-8 shadow-card dark:bg-dark-surface dark:border-dark-border space-y-6">
        <div class="pb-4 border-b border-border-main dark:border-dark-border">
            <h3 class="font-primary font-bold text-lg text-text-main dark:text-white">Formulir Pendaftaran Akun Pengguna</h3>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted mt-1">
                Daftarkan akun login baru untuk Administrator, Staf LPPM, atau Dosen Peneliti Universitas Insan Mahardika.
            </p>
        </div>

        @if($errors->any())
        <div class="p-4 rounded-2xl bg-alert-red-pale border border-alert-red/20 text-alert-red text-xs space-y-1">
            <span class="font-bold block">Terdapat kesalahan pengisian data:</span>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5 font-body text-xs">
            @csrf

            <div>
                <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">
                    Nama Lengkap Pengguna <span class="text-alert-red">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       required 
                       value="{{ old('name') }}" 
                       placeholder="Contoh: Dr. Apt. Rina Kusuma, M.Farm. atau Budi Santoso" 
                       class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white font-medium">
            </div>

            <div>
                <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">
                    Alamat Email Aktif <span class="text-alert-red">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       required 
                       value="{{ old('email') }}" 
                       placeholder="nama@mahardika.ac.id" 
                       class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white font-medium">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">
                        Password Login <span class="text-alert-red">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           required 
                           minlength="6" 
                           placeholder="Minimal 6 karakter" 
                           class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white font-medium">
                </div>

                <div>
                    <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">
                        Level Akses (Role) <span class="text-alert-red">*</span>
                    </label>
                    <select name="role" 
                            x-model="role" 
                            required 
                            class="w-full px-4 py-3 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white font-medium">
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                        <option value="staf" {{ old('role') === 'staf' ? 'selected' : '' }}>Staf LPPM (Operator)</option>
                        <option value="dosen" {{ old('role', 'dosen') === 'dosen' ? 'selected' : '' }}>Dosen (Peneliti / Pengabdi)</option>
                        <option value="reviewer" {{ old('role') === 'reviewer' ? 'selected' : '' }}>Reviewer Proposal</option>
                    </select>
                </div>
            </div>

            <!-- Hubungkan Dosen (Tampil jika role Dosen dipilih) -->
            <div x-show="role === 'dosen'" x-transition class="p-5 bg-mahardika-navy-pale/40 dark:bg-dark-surface-soft rounded-2xl border border-mahardika-navy/10 dark:border-dark-border space-y-2.5">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-mahardika-navy dark:bg-navy-accent"></span>
                    <label class="block font-bold uppercase text-[9px] text-mahardika-navy dark:text-navy-accent tracking-wider">
                        Hubungkan dengan Profil Dosen (Otomatis Relasi One-to-One)
                    </label>
                </div>
                <select name="dosen_id" class="w-full px-3.5 py-2.5 border border-border-main bg-white dark:bg-dark-surface dark:border-dark-border rounded-xl focus:outline-none text-xs dark:text-white font-medium">
                    <option value="">-- Pilih Profil Dosen (Opsional / Otomatis sesuai Email/Nama) --</option>
                    @foreach($dosens as $d)
                    <option value="{{ $d->id }}" {{ old('dosen_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama }} (NIDN: {{ $d->nidn }} • {{ $d->prodi }})
                    </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-text-secondary dark:text-dark-text-muted leading-relaxed">
                    Dengan menghubungkan akun ini ke profil dosen, dosen bersangkutan dapat langsung login ke portal, mengajukan proposal penelitian, mencatatkan HKI, dan memantau riwayat publikasi.
                </p>
            </div>

            <div class="pt-5 flex items-center justify-end space-x-3 border-t border-border-main dark:border-dark-border">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-3 rounded-xl border border-border-main font-bold hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent font-bold transition-all shadow-subtle flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan & Daftarkan Akun</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
