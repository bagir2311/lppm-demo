@extends('admin.layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Ringkasan LPPM')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome card header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-mahardika-navy to-[#1a3d6d] p-6 sm:p-8 text-white shadow-card dark:from-[#00183b] dark:to-dark-surface border border-white/10 dark:border-dark-border transition-colors duration-200">
        <div class="relative z-10 space-y-2.5">
            <span class="inline-block px-2.5 py-0.5 rounded bg-white/15 dark:bg-navy-accent/20 text-white dark:text-navy-accent font-primary font-bold text-[10px] uppercase tracking-wider">WORKSPACE STATUS ACTIVE</span>
            <h1 class="font-primary font-extrabold text-xl sm:text-2xl text-white">Selamat Datang Kembali, Administrator</h1>
            <p class="font-body text-xs text-mahardika-navy-pale/90 dark:text-slate-300 max-w-2xl leading-relaxed">
                Anda sedang mengelola sistem landing page dan repositori <strong class="text-white">LPPM Universitas Insan Mahardika</strong>. Gunakan panel navigasi samping untuk memperbarui data pengumuman, direktori riset dosen, repositori dokumen legal, link portal ekosistem, dan konfigurasi variabel global.
            </p>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat card 1: Lecturers -->
        <div class="p-6 bg-white border border-border-main rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex items-center justify-between hover:shadow-floating hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="space-y-1.5">
                <span class="text-[10px] text-text-secondary dark:text-dark-text-muted uppercase font-bold tracking-wider">Direktori Dosen</span>
                <span class="block font-primary font-extrabold text-2xl text-text-main">{{ $totalLecturers }} Dosen</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent flex items-center justify-center group-hover:scale-105 transition-transform duration-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
        </div>

        <!-- Stat card 2: Announcements -->
        <div class="p-6 bg-white border border-border-main rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex items-center justify-between hover:shadow-floating hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="space-y-1.5">
                <span class="text-[10px] text-text-secondary dark:text-dark-text-muted uppercase font-bold tracking-wider">Pengumuman Rilis</span>
                <span class="block font-primary font-extrabold text-2xl text-text-main">{{ $totalAnnouncements }} Rilis</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-alert-red-pale text-alert-red dark:bg-red-950/40 dark:text-red-400 flex items-center justify-center group-hover:scale-105 transition-transform duration-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
            </div>
        </div>

        <!-- Stat card 3: Documents -->
        <div class="p-6 bg-white border border-border-main rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex items-center justify-between hover:shadow-floating hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="space-y-1.5">
                <span class="text-[10px] text-text-secondary dark:text-dark-text-muted uppercase font-bold tracking-wider">Peraturan & SOP</span>
                <span class="block font-primary font-extrabold text-2xl text-text-main">{{ $totalDocs }} Berkas</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-success-green-pale text-success-green dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center group-hover:scale-105 transition-transform duration-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z" /></svg>
            </div>
        </div>

        <!-- Stat card 4: Popups -->
        <div class="p-6 bg-white border border-border-main rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border flex items-center justify-between hover:shadow-floating hover:-translate-y-0.5 transition-all duration-300 group">
            <div class="space-y-1.5">
                <span class="text-[10px] text-text-secondary dark:text-dark-text-muted uppercase font-bold tracking-wider">Popup Aktif</span>
                <span class="block font-primary font-extrabold text-2xl text-text-main">{{ $activePopups }} Pop-Up</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-accent text-mahardika-navy dark:bg-blue-950/40 dark:text-blue-400 flex items-center justify-center group-hover:scale-105 transition-transform duration-200 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
        </div>
    </div>

    <!-- Administrative Quick Action Center Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Quick Actions Card -->
        <div class="lg:col-span-6 bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border space-y-6 flex flex-col justify-between transition-colors duration-200">
            <div class="space-y-4">
                <div class="border-b border-border-main dark:border-dark-border pb-3">
                    <h3 class="font-primary font-bold text-base text-text-main uppercase tracking-wider">Kontrol Cepat Admin</h3>
                    <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted mt-1 leading-relaxed">
                        Akses cepat menu pengelolaan utama sistem CMS dan integrasi portal LPPM Mahardika.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <a href="{{ route('admin.settings') }}" class="p-4 rounded-2xl bg-bg-alt hover:bg-mahardika-navy-pale/60 dark:bg-dark-surface-soft dark:hover:bg-dark-surface-soft/80 border border-border-main/60 dark:border-dark-border text-left transition-all duration-150 group">
                        <span class="block font-bold text-xs text-text-main group-hover:text-mahardika-navy dark:group-hover:text-navy-accent">Ubah Headline Hero</span>
                        <span class="block text-[10px] text-text-secondary dark:text-dark-text-muted mt-1">Ubah headline landing page secara instan</span>
                    </a>
                    <a href="{{ route('admin.announcements') }}" class="p-4 rounded-2xl bg-bg-alt hover:bg-mahardika-navy-pale/60 dark:bg-dark-surface-soft dark:hover:bg-dark-surface-soft/80 border border-border-main/60 dark:border-dark-border text-left transition-all duration-150 group">
                        <span class="block font-bold text-xs text-text-main group-hover:text-mahardika-navy dark:group-hover:text-navy-accent">Buat Pengumuman Baru</span>
                        <span class="block text-[10px] text-text-secondary dark:text-dark-text-muted mt-1">Tambahkan pengumuman / hibah baru</span>
                    </a>
                    <a href="{{ route('admin.lecturers') }}" class="p-4 rounded-2xl bg-bg-alt hover:bg-mahardika-navy-pale/60 dark:bg-dark-surface-soft dark:hover:bg-dark-surface-soft/80 border border-border-main/60 dark:border-dark-border text-left transition-all duration-150 group">
                        <span class="block font-bold text-xs text-text-main group-hover:text-mahardika-navy dark:group-hover:text-navy-accent">Kelola Dosen Tetap</span>
                        <span class="block text-[10px] text-text-secondary dark:text-dark-text-muted mt-1">Edit data kepakaran, NIDN, & publikasi</span>
                    </a>
                    <a href="{{ route('portal.index') }}" class="p-4 rounded-2xl bg-bg-alt hover:bg-mahardika-navy-pale/60 dark:bg-dark-surface-soft dark:hover:bg-dark-surface-soft/80 border border-border-main/60 dark:border-dark-border text-left transition-all duration-150 group">
                        <span class="block font-bold text-xs text-text-main group-hover:text-mahardika-navy dark:group-hover:text-navy-accent">Buka Portal Layanan</span>
                        <span class="block text-[10px] text-text-secondary dark:text-dark-text-muted mt-1">Lihat katalog aplikasi civitas akademika</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Quick Session Resetter Card -->
        <div class="lg:col-span-6 bg-white border border-border-main p-6 sm:p-8 rounded-3xl shadow-card dark:bg-dark-surface dark:border-dark-border space-y-6 flex flex-col justify-between transition-colors duration-200">
            <div class="space-y-4">
                <div class="border-b border-border-main dark:border-dark-border pb-3">
                    <h3 class="font-primary font-bold text-base text-alert-red dark:text-red-400 uppercase tracking-wider">Sistem Pemulihan Data</h3>
                    <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted mt-1 leading-relaxed">
                        Data CMS superadmin tersimpan secara interaktif di dalam <strong>Laravel Session State</strong>. Data modifikasi yang Anda edit akan bertahan sepanjang sesi browser Anda aktif.
                    </p>
                </div>
                
                <p class="font-body text-xs text-text-secondary dark:text-dark-text-muted leading-relaxed">
                    Jika Anda ingin memulihkan seluruh data (Pengumuman, Dosen, Dokumen, Settings) kembali ke kondisi data akademis bawaan awal, klik tombol reset di bawah.
                </p>
            </div>
            
            <div class="pt-4 border-t border-border-main/40 dark:border-dark-border/40">
                <form action="{{ route('admin.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data kustom dan me-reset ke nilai default awal?')">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-primary font-bold text-xs bg-alert-red hover:bg-[#8e0707] text-white flex items-center justify-center space-x-2 transition-all duration-150 shadow-subtle hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2" /></svg>
                        <span>Pulihkan Database Awal (Reset Sesi)</span>
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
