@extends('layouts.app')

@section('title', 'Portal Layanan Terintegrasi - Universitas Insan Mahardika')

@section('content')
<div class="min-h-screen bg-bg-alt dark:bg-bg-main/60 pt-28 pb-20" x-data="{ 
    search: '', 
    activeTab: 'semua',
    apps: [
        {
            name: 'SIMPEN Research',
            category: 'dosen',
            description: 'Sistem Informasi Manajemen Penelitian Mahardika. Kelola proposal hibah, unggah logbook riset, dan input laporan kemajuan.',
            icon: 'flask',
            link: 'https://penelitian.mahardika.ac.id',
            status: 'online'
        },
        {
            name: 'SIMPENGMAS Portal',
            category: 'dosen',
            description: 'Sistem Informasi Manajemen Pengabdian Masyarakat. Kelola hibah pengabdian sosial, log kemajuan desa binaan, dan laporan luaran.',
            icon: 'hand-heart',
            link: 'https://pengmas.mahardika.ac.id',
            status: 'online'
        },
        {
            name: 'SIAKAD Mahasiswa',
            category: 'mahasiswa',
            description: 'Sistem Informasi Akademik Mahasiswa. Pengisian Kartu Rencana Studi (KRS), lihat Kartu Hasil Studi (KHS), transkip nilai, dan tagihan keuangan.',
            icon: 'academic',
            link: '#',
            status: 'online'
        },
        {
            name: 'KKN & Magang Portal',
            category: 'mahasiswa',
            description: 'Portal Pendaftaran Kuliah Kerja Nyata (KKN) dan Magang Industri. Pemilihan skema lokasi desa binaan, logbook, dan unggah laporan akhir.',
            icon: 'globe',
            link: '#',
            status: 'online'
        },
        {
            name: 'E-Presensi Pegawai',
            category: 'staf',
            description: 'Sistem Kehadiran & Aktivitas Harian Dosen dan Staf. Pencatatan presensi GPS, pengisian logbook harian, pengajuan cuti, dan lembur.',
            icon: 'clock',
            link: '#',
            status: 'online'
        },
        {
            name: 'SIPERPUS Digital Library',
            category: 'umum',
            description: 'Repositori & Perpustakaan Digital Mahardika. Akses katalog fisik, unduh berkas skripsi/tesis alumni, dan pinjam e-book terakreditasi.',
            icon: 'book-open',
            link: '#',
            status: 'online'
        },
        {
            name: 'SSO Account Central',
            category: 'staf',
            description: 'Pusat Keamanan & Manajemen Akun Global. Ubah kata sandi SSO, kelola verifikasi 2-faktor (2FA), dan audit perangkat aktif terhubung.',
            icon: 'shield',
            link: '#',
            status: 'online'
        },
        {
            name: 'OJS Kesehatan JKM',
            category: 'umum',
            description: 'Open Journal System Jurnal Kesehatan Mahardika. Pengajuan artikel ilmiah, pemantauan status penelaah sejawat, dan arsip publikasi.',
            icon: 'award',
            link: 'https://jurnal.mahardika.ac.id/index.php/jkm',
            status: 'online'
        }
    ]
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs Navigation -->
        <nav class="flex mb-8 text-xs font-semibold uppercase tracking-wider" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-text-disabled">
                <li class="inline-flex items-center">
                    <a href="{{ route('landing.index') }}" class="hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                        Beranda
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-text-disabled/60 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        <span class="text-text-main dark:text-navy-accent font-bold">Portal Layanan Terintegrasi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Dynamic Announcement / Alert SSO Banner -->
        <div class="bg-gradient-to-br from-mahardika-navy to-[#1a3d6d] text-white p-6 sm:p-8 rounded-3xl shadow-floating dark:from-dark-surface dark:to-dark-surface-soft border border-white/10 dark:border-dark-border mb-12 relative overflow-hidden group">
            <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none group-hover:scale-105 transition-transform duration-500">
                <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v6h-2V7zm0 8h2v2h-2v-2z"/></svg>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded bg-gold-accent/20 text-gold-accent border border-gold-accent/30 font-primary font-bold text-[10px] uppercase tracking-wider">
                        <span>PENGUMUMAN IMPLEMENTASI SSO</span>
                    </div>
                    <h2 class="font-primary font-extrabold text-xl sm:text-2xl text-white leading-tight">Keamanan Akun Terpusat (Keycloak SSO)</h2>
                    <p class="font-body text-xs text-mahardika-navy-pale/85 max-w-3xl leading-relaxed">
                        Tahap berikutnya: halaman portal ekosistem ini akan diamankan menggunakan <strong>SSO Mahardika berbasis Keycloak</strong>. Seluruh civitas akademika nantinya hanya membutuhkan satu akun tunggal untuk masuk ke seluruh aplikasi terintegrasi.
                    </p>
                </div>
                <!-- SSO Link Redirect Button -->
                <a href="{{ route('sso.login') }}" 
                   class="w-full md:w-auto px-5 py-3 rounded-xl font-primary font-bold text-center text-xs bg-gold-accent text-mahardika-navy hover:bg-amber-400 shadow-subtle flex items-center justify-center space-x-2 shrink-0 transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    <span>Uji Coba Hubungkan SSO</span>
                </a>
            </div>
        </div>

        <!-- Interactive Header with Live Filters and Search Bar -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8">
            <div>
                <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Digital Gateway</span>
                <h2 class="font-primary font-extrabold text-3xl text-text-main mt-2 leading-none">Ekosistem Aplikasi LPPM</h2>
                <div class="h-1.5 w-16 bg-success-green mt-4 rounded-full"></div>
            </div>
            
            <!-- Controls Container -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full lg:max-w-2xl">
                <!-- Live Search -->
                <div class="relative flex-1">
                    <input type="text" 
                           x-model="search"
                           placeholder="Cari nama aplikasi atau deskripsi..." 
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-border-main bg-bg-surface focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:bg-dark-surface dark:border-dark-border dark:focus:ring-navy-accent/25 text-sm">
                    <svg class="w-5 h-5 text-text-disabled absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>

        <!-- Roles Tab Filter -->
        <div class="flex flex-wrap items-center gap-2 mb-10 pb-2 border-b border-border-main/50 dark:border-dark-border/40">
            <button @click="activeTab = 'semua'" :class="{ 'border-b-2 border-mahardika-navy text-mahardika-navy font-bold dark:border-navy-accent dark:text-navy-accent': activeTab === 'semua', 'text-text-sub hover:text-mahardika-navy dark:hover:text-white': activeTab !== 'semua' }" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider transition-all duration-150">Semua Portal</button>
            <button @click="activeTab = 'mahasiswa'" :class="{ 'border-b-2 border-mahardika-navy text-mahardika-navy font-bold dark:border-navy-accent dark:text-navy-accent': activeTab === 'mahasiswa', 'text-text-sub hover:text-mahardika-navy dark:hover:text-white': activeTab !== 'mahasiswa' }" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider transition-all duration-150">Mahasiswa</button>
            <button @click="activeTab = 'dosen'" :class="{ 'border-b-2 border-mahardika-navy text-mahardika-navy font-bold dark:border-navy-accent dark:text-navy-accent': activeTab === 'dosen', 'text-text-sub hover:text-mahardika-navy dark:hover:text-white': activeTab !== 'dosen' }" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider transition-all duration-150">Dosen & Peneliti</button>
            <button @click="activeTab = 'staf'" :class="{ 'border-b-2 border-mahardika-navy text-mahardika-navy font-bold dark:border-navy-accent dark:text-navy-accent': activeTab === 'staf', 'text-text-sub hover:text-mahardika-navy dark:hover:text-white': activeTab !== 'staf' }" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider transition-all duration-150">Staf & Administrasi</button>
            <button @click="activeTab = 'umum'" :class="{ 'border-b-2 border-mahardika-navy text-mahardika-navy font-bold dark:border-navy-accent dark:text-navy-accent': activeTab === 'umum', 'text-text-sub hover:text-mahardika-navy dark:hover:text-white': activeTab !== 'umum' }" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider transition-all duration-150">Publik / Umum</button>
        </div>

        <!-- Apps Catalog Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="app in apps">
                <div x-show="(activeTab === 'semua' || app.category === activeTab) && (search === '' || app.name.toLowerCase().includes(search.toLowerCase()) || app.description.toLowerCase().includes(search.toLowerCase()))"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="p-6 rounded-3xl bg-white border border-border-main shadow-card hover:shadow-floating hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border flex flex-col justify-between group">
                    
                    <div class="space-y-5">
                        <!-- Top Row: Icon and Status Dot -->
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-subtle transition-transform duration-300 group-hover:scale-105"
                                 :class="{
                                     'bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent': app.category === 'dosen',
                                     'bg-success-green-pale text-success-green': app.category === 'mahasiswa',
                                     'bg-[#ecfdf5] text-[#059669]': app.category === 'staf',
                                     'bg-sky-accent text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent': app.category === 'umum'
                                 }">
                                <!-- Category Icons -->
                                <template x-if="app.icon === 'flask'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                </template>
                                <template x-if="app.icon === 'hand-heart'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </template>
                                <template x-if="app.icon === 'academic'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                                </template>
                                <template x-if="app.icon === 'globe'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                </template>
                                <template x-if="app.icon === 'clock'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </template>
                                <template x-if="app.icon === 'book-open'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </template>
                                <template x-if="app.icon === 'shield'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </template>
                                <template x-if="app.icon === 'award'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                                </template>
                            </div>

                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-success-green-pale text-success-green border border-success-green/10">
                                <span class="w-1.5 h-1.5 rounded-full bg-success-green mr-1.5 animate-pulse"></span> Aktif
                            </span>
                        </div>

                        <!-- Brand Title & Category -->
                        <div class="space-y-1.5">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-text-disabled" x-text="app.category"></span>
                            <h3 class="font-primary font-bold text-lg text-text-main group-hover:text-mahardika-navy dark:group-hover:text-navy-accent transition-colors duration-150" x-text="app.name"></h3>
                            <p class="font-body text-xs text-text-secondary leading-relaxed line-clamp-3 dark:text-dark-text-muted" x-text="app.description"></p>
                        </div>
                    </div>

                    <!-- CTA Actions -->
                    <div class="mt-4 pt-3 border-t border-gray-200 flex">
                        <a :href="app.link" 
                           :target="app.link !== '#' ? '_blank' : '_self'" 
                           @click="app.link === '#' ? alert('Aplikasi ini terdaftar di Portal, dan akan dibuka setelah otentikasi SSO Keycloak diaktifkan.') : null"
                           style="background-color: #15171eff !important; color: #ffffff !important; display: flex !important; align-items: center !important; justify-content: center !important; width: 100% !important; padding: 10px !important; border-radius: 12px !important; font-weight: bold !important; font-size: 12px !important; text-decoration: none !important;">
                            <span>Luncurkan Aplikasi</span>
                            <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>

                </div>
            </template>
        </div>

        <!-- Search fallback message shown if no cards are displayed -->
        <div x-show="apps.filter(app => (activeTab === 'semua' || app.category === activeTab) && (search === '' || app.name.toLowerCase().includes(search.toLowerCase()) || app.description.toLowerCase().includes(search.toLowerCase()))).length === 0"
             class="text-center py-20 bg-white border border-dashed border-border-main rounded-3xl mt-8 dark:bg-dark-surface dark:border-dark-border"
             style="display: none;">
            <svg class="w-12 h-12 text-text-disabled mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <h4 class="font-primary font-bold text-base text-text-main">Aplikasi tidak ditemukan</h4>
            <p class="font-body text-xs text-text-secondary mt-1 dark:text-dark-text-muted">Cobalah memasukkan kata kunci lain yang sesuai dengan kebutuhan peran Anda.</p>
        </div>

    </div>
</div>
@endsection
