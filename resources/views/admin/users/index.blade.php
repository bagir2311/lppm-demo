@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna & Staf')
@section('page-title', 'Manajemen Akun Pengguna & Staf LPPM')

@section('content')
<div x-data="{ 
    addModalOpen: false, 
    editModalOpen: false,
    newRole: 'dosen',
    search: '{{ addslashes($searchQuery ?? '') }}',
    roleFilter: '{{ addslashes($roleFilter ?? 'all') }}',
    editItem: { id: '', name: '', email: '', role: 'dosen', dosen_id: '' },
    openEdit(user) {
        this.editItem = {
            id: user.id,
            name: user.name,
            email: user.email,
            role: user.role === 'admin_lppm' ? 'admin' : (user.role === 'staff' ? 'staf' : user.role),
            dosen_id: user.dosen_id || ''
        };
        this.editModalOpen = true;
    },
    matchesSearch(name, email, role) {
        let matchText = true;
        if (this.search && this.search.trim()) {
            let q = this.search.toLowerCase().trim();
            matchText = (name || '').toLowerCase().includes(q) || (email || '').toLowerCase().includes(q);
        }
        let matchRole = true;
        if (this.roleFilter && this.roleFilter !== 'all') {
            if (this.roleFilter === 'admin') {
                matchRole = (role === 'admin' || role === 'admin_lppm');
            } else if (this.roleFilter === 'staf') {
                matchRole = (role === 'staf' || role === 'staff');
            } else {
                matchRole = (role === this.roleFilter);
            }
        }
        return matchText && matchRole;
    }
}" class="space-y-8">

    <!-- Header Actions Card -->
    <div class="bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-1">
            <h3 class="font-primary font-bold text-base text-text-main dark:text-white">Manajemen Akun Pengguna & Staf</h3>
            <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted">
                Kelola hak akses pengguna portal LPPM (Administrator, Staf, Dosen, dan Reviewer) serta sinkronisasi akun login dengan data profil dosen.
            </p>
        </div>
        <button @click="addModalOpen = true" class="px-5 py-3 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle flex items-center space-x-2 transition-all duration-200 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft shrink-0">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
            <span>Tambah Akun Baru</span>
        </button>
    </div>

    <!-- Statistics Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="p-5 bg-white border border-border-main rounded-2xl shadow-card dark:bg-dark-surface dark:border-dark-border flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <div>
                <span class="block text-[11px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted">Total Pengguna</span>
                <span class="font-primary font-extrabold text-2xl text-text-main dark:text-white mt-0.5 block">{{ $totalUsers }}</span>
            </div>
        </div>

        <!-- Total Admin -->
        <div class="p-5 bg-white border border-border-main rounded-2xl shadow-card dark:bg-dark-surface dark:border-dark-border flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-alert-red-pale text-alert-red dark:bg-dark-surface-soft flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </div>
            <div>
                <span class="block text-[11px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted">Administrator</span>
                <span class="font-primary font-extrabold text-2xl text-alert-red mt-0.5 block">{{ $totalAdmin }}</span>
            </div>
        </div>

        <!-- Total Staf -->
        <div class="p-5 bg-white border border-border-main rounded-2xl shadow-card dark:bg-dark-surface dark:border-dark-border flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-success-green-pale text-success-green dark:bg-dark-surface-soft flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <span class="block text-[11px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted">Staf LPPM</span>
                <span class="font-primary font-extrabold text-2xl text-success-green mt-0.5 block">{{ $totalStaf }}</span>
            </div>
        </div>

        <!-- Total Dosen -->
        <div class="p-5 bg-white border border-border-main rounded-2xl shadow-card dark:bg-dark-surface dark:border-dark-border flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-[#0284c7]/10 text-[#0284c7] dark:bg-dark-surface-soft flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div>
                <span class="block text-[11px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted">Akun Dosen</span>
                <span class="font-primary font-extrabold text-2xl text-[#0284c7] mt-0.5 block">{{ $totalDosen }}</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white border border-border-main p-4 sm:p-5 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <!-- Search Input -->
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-disabled">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" 
                       x-model="search" 
                       placeholder="Cari nama atau email akun..." 
                       class="w-full pl-10 pr-9 py-2.5 text-xs border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:focus:ring-navy-accent/20 transition-all text-text-main placeholder:text-text-disabled">
                <button x-show="search && search.length > 0" 
                        @click="search = ''" 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-text-disabled hover:text-text-main transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Role Filter Select -->
            <div class="relative w-full sm:w-48">
                <select x-model="roleFilter" class="w-full px-3.5 py-2.5 text-xs border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none appearance-none cursor-pointer text-text-main dark:text-white font-medium">
                    <option value="all">Semua Level Akses</option>
                    <option value="admin">Administrator</option>
                    <option value="staf">Staf LPPM</option>
                    <option value="dosen">Dosen</option>
                    <option value="reviewer">Reviewer</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-text-disabled">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2 text-xs font-body text-text-secondary dark:text-dark-text-muted">
            <span class="w-2 h-2 rounded-full bg-success-green animate-pulse"></span>
            <span>Tabel Sinkronisasi & Filter Aktif</span>
        </div>
    </div>

    <!-- Table of Users -->
    <div class="bg-white border border-border-main rounded-3xl overflow-hidden shadow-card dark:bg-dark-surface dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-bg-alt border-b border-border-main/80 text-[10px] uppercase font-bold text-text-secondary dark:bg-dark-surface-soft/40 dark:border-dark-border">
                        <th class="p-4 w-12 text-center">No</th>
                        <th class="p-4">Nama Pengguna & Email</th>
                        <th class="p-4 w-36">Level Akses (Role)</th>
                        <th class="p-4">Profil Dosen Terhubung</th>
                        <th class="p-4 w-32">Dibuat</th>
                        <th class="p-4 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-main/50 dark:divide-dark-border/40 font-body text-xs text-text-secondary dark:text-dark-text-muted">
                    @forelse($users as $index => $u)
                    @php
                        $userRole = $u->role;
                        $linkedDosen = $u->dosen ?? $u->lecturer ?? \App\Models\Dosen::where('email', $u->email)->first();
                    @endphp
                    <tr x-show="matchesSearch('{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}', '{{ $userRole }}')"
                        class="hover:bg-mahardika-navy-pale/20 dark:hover:bg-dark-surface-soft/20 transition-colors duration-150">
                        <td class="p-4 text-center text-text-disabled font-semibold">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-mahardika-navy-pale dark:bg-dark-surface-soft text-mahardika-navy dark:text-navy-accent font-primary font-bold text-xs flex items-center justify-center shrink-0 border border-mahardika-navy/10">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-text-main dark:text-white truncate">{{ $u->name }}</span>
                                    <span class="text-[11px] text-text-secondary dark:text-dark-text-muted truncate">{{ $u->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            @if(in_array($userRole, ['admin', 'admin_lppm']))
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-alert-red-pale text-alert-red border border-alert-red/20 inline-flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-alert-red"></span>
                                    <span>Administrator</span>
                                </span>
                            @elseif(in_array($userRole, ['staf', 'staff']))
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-success-green-pale text-success-green border border-success-green/20 inline-flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success-green"></span>
                                    <span>Staf LPPM</span>
                                </span>
                            @elseif($userRole === 'reviewer')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#8b5cf6]/10 text-[#8b5cf6] border border-[#8b5cf6]/20 inline-flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#8b5cf6]"></span>
                                    <span>Reviewer</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#0284c7]/10 text-[#0284c7] border border-[#0284c7]/20 inline-flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#0284c7]"></span>
                                    <span>Dosen</span>
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($userRole === 'dosen')
                                @if($linkedDosen)
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-success-green shrink-0"></span>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-text-main dark:text-white">{{ $linkedDosen->nama }}</span>
                                            <span class="text-[10px] text-text-disabled">NIDN: {{ $linkedDosen->nidn ?? '-' }} • {{ $linkedDosen->prodi ?? '-' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-alert-red-pale text-alert-red dark:bg-dark-surface-soft inline-flex items-center space-x-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        Belum Terhubung
                                    </span>
                                @endif
                            @elseif(in_array($userRole, ['admin', 'admin_lppm', 'staf', 'staff']))
                                <span class="text-[11px] text-text-disabled italic font-medium">Akses Sistem LPPM</span>
                            @else
                                <span class="text-[11px] text-text-disabled italic font-medium">Akun Reviewer Proposal</span>
                            @endif
                        </td>
                        <td class="p-4 text-xs text-text-disabled">
                            {{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-1.5">
                                <!-- Tombol Edit -->
                                <button @click="openEdit({{ json_encode($u) }})" 
                                        class="p-2 rounded-lg border border-border-main hover:bg-mahardika-navy-pale text-mahardika-navy dark:border-dark-border dark:text-white dark:hover:bg-dark-surface-soft transition-colors duration-150" 
                                        title="Edit Akun">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>

                                <!-- Tombol Hapus -->
                                @if(auth()->id() !== $u->id)
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ addslashes($u->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 rounded-lg border border-alert-red/30 hover:bg-alert-red-pale text-alert-red transition-colors duration-150" 
                                            title="Hapus Akun">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-text-disabled">Belum ada akun pengguna yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-border-main dark:border-dark-border">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL TAMBAH PENGGUNA BARU -->
    <div x-show="addModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="addModalOpen" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100" 
                 class="relative bg-white border border-border-main rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl dark:bg-dark-surface dark:border-dark-border z-10">
                
                <div class="flex items-center justify-between pb-4 border-b border-border-main dark:border-dark-border mb-6">
                    <div>
                        <h4 class="font-primary font-bold text-lg text-text-main dark:text-white">Tambah Akun Pengguna Baru</h4>
                        <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted mt-0.5">Buat kredensial login staf atau dosen portal LPPM.</p>
                    </div>
                    <button @click="addModalOpen = false" class="text-text-disabled hover:text-text-main dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4 font-body text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">Nama Lengkap Pengguna <span class="text-alert-red">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Rina Kusuma atau Staf Riset LPPM" class="w-full px-4 py-2.5 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white">
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">Alamat Email Login <span class="text-alert-red">*</span></label>
                        <input type="email" name="email" required placeholder="nama@mahardika.ac.id" class="w-full px-4 py-2.5 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">Password Awal <span class="text-alert-red">*</span></label>
                            <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white">
                        </div>

                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">Level Akses (Role) <span class="text-alert-red">*</span></label>
                            <select name="role" x-model="newRole" required class="w-full px-4 py-2.5 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white">
                                <option value="admin">Administrator (Akses Penuh)</option>
                                <option value="staf">Staf LPPM (Operator)</option>
                                <option value="dosen" selected>Dosen (Peneliti / Pengabdi)</option>
                                <option value="reviewer">Reviewer Proposal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Hubungkan Dosen (Tampil jika role Dosen dipilih) -->
                    <div x-show="newRole === 'dosen'" x-transition class="p-4 bg-mahardika-navy-pale/40 dark:bg-dark-surface-soft rounded-2xl border border-mahardika-navy/10 dark:border-dark-border space-y-2">
                        <label class="block font-bold uppercase text-[9px] text-mahardika-navy dark:text-navy-accent">
                            Hubungkan dengan Profil Dosen (Otomatis Relasi One-to-One)
                        </label>
                        <select name="dosen_id" class="w-full px-3 py-2 border border-border-main bg-white dark:bg-dark-surface dark:border-dark-border rounded-xl focus:outline-none text-xs dark:text-white">
                            <option value="">-- Pilih Profil Dosen (Opsional / Otomatis sesuai Email) --</option>
                            @foreach($dosens as $d)
                            <option value="{{ $d->id }}">
                                {{ $d->nama }} (NIDN: {{ $d->nidn }} • {{ $d->prodi }})
                            </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-text-secondary dark:text-dark-text-muted">
                            Akun login dosen ini akan terhubung langsung dengan tabel direktori dosen, memungkinkan dosen mengunggah proposal dan memantau riwayat riset.
                        </p>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3 border-t border-border-main dark:border-dark-border">
                        <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 rounded-xl border border-border-main font-bold hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent font-bold transition-all shadow-subtle">Simpan Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT PENGGUNA -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="editModalOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="editModalOpen" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100" 
                 class="relative bg-white border border-border-main rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl dark:bg-dark-surface dark:border-dark-border z-10">
                
                <div class="flex items-center justify-between pb-4 border-b border-border-main dark:border-dark-border mb-6">
                    <div>
                        <h4 class="font-primary font-bold text-lg text-text-main dark:text-white">Edit Akun Pengguna</h4>
                        <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted mt-0.5">Perbarui informasi kredensial, role, atau relasi profil dosen.</p>
                    </div>
                    <button @click="editModalOpen = false" class="text-text-disabled hover:text-text-main dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form :action="'{{ url('/admin/users') }}/' + editItem.id" method="POST" class="space-y-4 font-body text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">Nama Lengkap Pengguna <span class="text-alert-red">*</span></label>
                        <input type="text" name="name" x-model="editItem.name" required class="w-full px-4 py-2.5 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white">
                    </div>

                    <div>
                        <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">Alamat Email Login <span class="text-alert-red">*</span></label>
                        <input type="email" name="email" x-model="editItem.email" required class="w-full px-4 py-2.5 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">Password Baru (Opsional)</label>
                            <input type="password" name="password" minlength="6" placeholder="Kosongkan jika tetap" class="w-full px-4 py-2.5 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white">
                        </div>

                        <div>
                            <label class="block font-bold uppercase text-[9px] text-text-secondary dark:text-dark-text-muted mb-1.5">Level Akses (Role) <span class="text-alert-red">*</span></label>
                            <select name="role" x-model="editItem.role" required class="w-full px-4 py-2.5 border border-border-main bg-bg-surface dark:bg-dark-surface-soft dark:border-dark-border rounded-xl focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:text-white">
                                <option value="admin">Administrator (Akses Penuh)</option>
                                <option value="staf">Staf LPPM (Operator)</option>
                                <option value="dosen">Dosen (Peneliti / Pengabdi)</option>
                                <option value="reviewer">Reviewer Proposal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Hubungkan Dosen (Tampil jika role Dosen dipilih) -->
                    <div x-show="editItem.role === 'dosen'" x-transition class="p-4 bg-mahardika-navy-pale/40 dark:bg-dark-surface-soft rounded-2xl border border-mahardika-navy/10 dark:border-dark-border space-y-2">
                        <label class="block font-bold uppercase text-[9px] text-mahardika-navy dark:text-navy-accent">
                            Hubungkan dengan Profil Dosen (Otomatis Relasi One-to-One)
                        </label>
                        <select name="dosen_id" x-model="editItem.dosen_id" class="w-full px-3 py-2 border border-border-main bg-white dark:bg-dark-surface dark:border-dark-border rounded-xl focus:outline-none text-xs dark:text-white">
                            <option value="">-- Pilih Profil Dosen --</option>
                            @foreach($dosens as $d)
                            <option value="{{ $d->id }}">
                                {{ $d->nama }} (NIDN: {{ $d->nidn }} • {{ $d->prodi }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3 border-t border-border-main dark:border-dark-border">
                        <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 rounded-xl border border-border-main font-bold hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-mahardika-navy text-white hover:bg-mahardika-navy-soft dark:bg-navy-accent font-bold transition-all shadow-subtle">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
