@extends('layouts.app')

@section('title', 'LPPM Universitas Insan Mahardika - Lembaga Penelitian & Pengabdian kepada Masyarakat')

@section('content')

<!-- Section 2: Hero Section -->
<section id="hero" class="relative overflow-hidden bg-gradient-to-br from-bg-main via-bg-alt to-mahardika-navy-pale/40 pt-10 pb-20 lg:pt-16 lg:pb-32 dark:from-bg-main dark:via-bg-surface dark:to-dark-surface-soft/20">
    <!-- Abstract Background Pattern Vectors -->
    <div class="absolute inset-0 z-0 opacity-30 pointer-events-none dark:opacity-10">
        <svg class="absolute right-0 top-0 w-1/2 h-full text-mahardika-navy-pale dark:text-dark-border" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
            <polygon points="50,0 100,0 100,100 0,100" />
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Text Column -->
            <div class="lg:col-span-7 space-y-8 animate-fade-in-up">
                <!-- Brand Logo / Fallback Header -->
                <div class="flex items-center space-x-3 mb-4">
                    @if(file_exists(public_path('images/logo-mahardika.png')))
                        <img src="{{ asset('images/logo-mahardika.png') }}" alt="Logo Universitas Insan Mahardika" class="h-10 w-auto object-contain dark:brightness-110">
                        <div class="flex flex-col">
                            <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent leading-none">Universitas Insan Mahardika</span>
                            <span class="text-[9px] text-text-disabled uppercase font-semibold mt-1">LPPM Portal Resmi</span>
                        </div>
                    @else
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-xl bg-mahardika-navy text-white dark:bg-navy-accent font-primary font-extrabold text-xs uppercase tracking-wider shadow-subtle">
                            <!-- Academic Shield Vector Icon -->
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                            <span>Universitas Insan Mahardika</span>
                        </div>
                    @endif
                </div>

                <!-- Academic Badge -->
                <div class="inline-flex items-center space-x-2 bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent px-3 py-1.5 rounded-full border border-mahardika-navy/10 dark:border-dark-border">
                    <span class="flex h-2 w-2 rounded-full bg-mahardika-navy dark:bg-navy-accent animate-pulse"></span>
                    <span class="font-body text-xs font-bold uppercase tracking-wider">Klasterisasi SINTA Madya</span>
                </div>
                
                <h1 class="font-primary font-extrabold text-4xl sm:text-5xl lg:text-6xl tracking-tight text-text-main leading-[1.1]">
                    @if(isset($settings['hero_headline']) && $settings['hero_headline'] !== 'Lembaga Penelitian dan Pengabdian kepada Masyarakat')
                        {{ $settings['hero_headline'] }}
                    @else
                        Lembaga Penelitian dan <span class="text-mahardika-navy dark:text-navy-accent">Pengabdian kepada Masyarakat</span>
                    @endif
                    <span class="block text-xs sm:text-sm font-body font-bold text-mahardika-navy/70 dark:text-navy-accent/70 tracking-widest uppercase mt-3">
                        Universitas Insan Mahardika
                    </span>
                </h1>
                
                <p class="font-body text-base sm:text-lg text-text-secondary leading-relaxed max-w-2xl dark:text-dark-text-muted">
                    {{ $settings['hero_subheadline'] ?? 'Portal resmi LPPM Universitas Insan Mahardika. Wadah kolaborasi, fasilitasi, dan diseminasi hasil karya ilmiah, inovasi, publikasi jurnal terakreditasi, serta pengabdian masyarakat yang terintegrasi.' }}
                </p>

                <!-- Dual CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <a href="#pengumuman" class="px-6 py-3.5 rounded-xl font-primary font-bold text-center text-sm bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-floating hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft">
                        Lihat Pengumuman
                    </a>
                    <a href="#portal" class="px-6 py-3.5 rounded-xl font-primary font-bold text-center text-sm bg-white text-mahardika-navy border border-border-main hover:bg-mahardika-navy-pale shadow-subtle hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 dark:bg-dark-surface dark:text-white dark:border-dark-border dark:hover:bg-dark-surface-soft">
                        Ajukan Penelitian
                    </a>
                    <a href="#alur" class="group inline-flex items-center justify-center font-primary font-bold text-sm text-mahardika-navy hover:text-mahardika-navy-soft dark:text-navy-accent dark:hover:text-white transition-colors duration-150 py-2">
                        <span>Alur Pengajuan</span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right Visual Composition Column -->
            <div class="lg:col-span-5 relative mt-6 lg:mt-0">
                <div class="relative w-full max-w-md mx-auto aspect-square flex items-center justify-center">
                    
                    <!-- Core Abstract visual card backdrop -->
                    <div class="absolute w-72 h-72 rounded-3xl bg-gradient-to-br from-mahardika-navy/10 to-success-green/10 dark:from-dark-border/40 dark:to-dark-surface-soft/10 animate-pulse"></div>

                    <!-- Main abstract visual panel representing research -->
                    <div class="absolute top-4 left-4 w-64 p-5 rounded-2xl bg-white border border-border-main shadow-card dark:bg-dark-surface dark:border-dark-border hover:scale-105 transition-transform duration-300">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-mahardika-navy-pale dark:bg-dark-surface-soft flex items-center justify-center text-mahardika-navy dark:text-navy-accent">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-primary font-bold text-xs text-text-main leading-tight">Simpen Portal</span>
                                <span class="text-[10px] text-text-secondary dark:text-dark-text-muted">Research System</span>
                            </div>
                        </div>
                        <div class="h-2 w-full bg-border-main rounded-full overflow-hidden dark:bg-dark-border">
                            <div class="h-full bg-mahardika-navy rounded-full dark:bg-navy-accent" style="width: 75%"></div>
                        </div>
                        <div class="flex justify-between items-center mt-3 text-[10px] text-text-secondary dark:text-dark-text-muted">
                            <span>Reviewer Assigned</span>
                            <span class="font-bold text-success-green">Approved (3)</span>
                        </div>
                    </div>

                    <!-- Overlapping Card 2: Community Service (Pengmas) -->
                    <div class="absolute bottom-6 right-4 w-60 p-5 rounded-2xl bg-white border border-border-main shadow-floating dark:bg-dark-surface dark:border-dark-border hover:scale-105 transition-transform duration-300">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-9 h-9 rounded-lg bg-success-green-pale flex items-center justify-center text-success-green">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-primary font-bold text-xs text-text-main leading-tight">Pengmas Active</span>
                                <span class="text-[9px] text-text-secondary dark:text-dark-text-muted">Simpengmas Portal</span>
                            </div>
                        </div>
                        <span class="text-xl font-bold font-primary text-text-main">25 Skema</span>
                        <p class="text-[9px] text-text-secondary leading-tight mt-1 dark:text-dark-text-muted">Sedang diimplementasikan di berbagai desa mitra binaan LPPM.</p>
                    </div>

                    <!-- Small Stat Tag Overlay -->
                    <div class="absolute top-12 right-0 bg-white border border-border-main p-3.5 rounded-xl shadow-card flex items-center space-x-2.5 dark:bg-dark-surface dark:border-dark-border animate-bounce duration-[3000ms]">
                        <div class="w-7 h-7 rounded bg-gold-accent/15 flex items-center justify-center text-gold-accent">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-text-secondary dark:text-dark-text-muted uppercase tracking-wider font-bold">Akreditasi JKM</span>
                            <span class="text-xs font-bold text-text-main">SINTA 3 Certified</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Section 3: Section Statistik -->
<section class="relative bg-bg-main -mt-10 lg:-mt-16 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-bg-surface border border-border-main/80 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-floating dark:bg-dark-surface dark:border-dark-border">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 lg:gap-4 divide-y md:divide-y-0 md:divide-x divide-border-main dark:divide-dark-border">
                @foreach($statistics as $stat)
                <div class="flex flex-col items-center text-center p-3 {{ $loop->iteration > 2 ? 'pt-8 md:pt-3' : '' }} {{ $loop->iteration > 3 ? 'pt-8 lg:pt-3' : '' }}">
                    <div class="w-12 h-12 rounded-xl {{ $stat['bg'] }} flex items-center justify-center {{ $stat['text'] }} mb-4">
                        @if($stat['icon'] == 'users')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        @elseif($stat['icon'] == 'flask')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        @elseif($stat['icon'] == 'hand-heart')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        @elseif($stat['icon'] == 'book-open')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        @elseif($stat['icon'] == 'award')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        @endif
                    </div>
                    <span class="text-3xl font-extrabold font-primary text-text-main tracking-tight">{{ $stat['value'] }}</span>
                    <span class="text-xs text-text-secondary uppercase tracking-wider font-semibold mt-1 dark:text-dark-text-muted">{{ $stat['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Section 4: Section Profil LPPM -->
<section id="profil" class="py-20 lg:py-28 bg-bg-main relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Text -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Profil Lembaga</span>
            <h2 class="font-primary font-extrabold text-3xl sm:text-4xl text-text-main mt-2 leading-tight">
                Membangun Kemandirian Riset dan Pengabdian Masyarakat
            </h2>
            <div class="h-1.5 w-16 bg-gold-accent mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left Info Block -->
            <div class="lg:col-span-6 space-y-6">
                <h3 class="font-primary font-bold text-2xl text-text-main">Tentang LPPM Mahardika</h3>
                <p class="font-body text-sm text-text-secondary leading-relaxed dark:text-dark-text-muted">
                    LPPM Universitas Insan Mahardika merupakan unsur pelaksana akademik di tingkat universitas yang bertugas menyelenggarakan, memantau, mengukur, serta mengembangkan kegiatan penelitian dan pengabdian kepada masyarakat di lingkungan kampus Mahardika.
                </p>
                <p class="font-body text-sm text-text-secondary leading-relaxed dark:text-dark-text-muted">
                    Kami senantiasa bermitra dengan masyarakat luas, sektor swasta, dunia industri (DUDI), dan instansi pemerintah guna mengimplementasikan hasil penelitian orisinal yang berorientasi pada penyelesaian masalah nyata demi kemaslahatan masyarakat umum.
                </p>

                <!-- Vision & Mission Accordion -->
                <div x-data="{ activeTab: 'visi' }" class="border border-border-main rounded-2xl overflow-hidden bg-bg-surface dark:bg-dark-surface dark:border-dark-border">
                    <div class="flex border-b border-border-main dark:border-dark-border">
                        <button @click="activeTab = 'visi'" :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent': activeTab === 'visi', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': activeTab !== 'visi' }" class="flex-1 py-3 font-primary font-bold text-xs transition-colors duration-150 uppercase tracking-wider">Visi</button>
                        <button @click="activeTab = 'misi'" :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent': activeTab === 'misi', 'text-text-sub hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft': activeTab !== 'misi' }" class="flex-1 py-3 font-primary font-bold text-xs transition-colors duration-150 uppercase tracking-wider">Misi</button>
                    </div>
                    <div class="p-5 font-body text-xs text-text-secondary leading-relaxed dark:text-dark-text-muted">
                        <!-- Vision Panel -->
                        <div x-show="activeTab === 'visi'" class="space-y-2 animate-fade-in-up">
                            <p class="font-semibold text-text-main text-sm">"Menjadi Pusat Inovasi Riset Unggul dan Pelopor Pemberdayaan Masyarakat Berkelanjutan Tingkat Nasional Pada Tahun 2030."</p>
                        </div>
                        <!-- Mission Panel -->
                        <div x-show="activeTab === 'misi'" class="space-y-2.5 list-decimal animate-fade-in-up" style="display: none;">
                            <div class="flex items-start space-x-2">
                                <span class="font-bold text-mahardika-navy dark:text-navy-accent">1.</span>
                                <span>Menyelenggarakan penelitian bermutu tinggi dan berdaya saing nasional guna memajukan keilmuan dan luaran paten.</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="font-bold text-mahardika-navy dark:text-navy-accent">2.</span>
                                <span>Mengaplikasikan hasil riset lintas keilmuan secara nyata dalam pemberdayaan sosial dan pembangunan ekonomi kemasyarakatan.</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="font-bold text-mahardika-navy dark:text-navy-accent">3.</span>
                                <span>Memfasilitasi luaran publikasi ilmiah pada jurnal nasional terakreditasi dan jurnal internasional bereputasi tinggi.</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <span class="font-bold text-mahardika-navy dark:text-navy-accent">4.</span>
                                <span>Mengembangkan jaringan kemitraan strategis dengan institusi pemerintah, industri, komunitas, dan universitas mitra.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 4 Core Pillars Grid -->
            <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- Pillar 1: Penelitian -->
                <div class="p-6 rounded-2xl bg-bg-surface border border-border-main shadow-subtle hover:shadow-card hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border">
                    <div class="w-10 h-10 rounded-xl bg-mahardika-navy-pale flex items-center justify-center text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    </div>
                    <h4 class="font-primary font-bold text-base text-text-main mb-2">Penelitian Berkualitas</h4>
                    <p class="font-body text-xs text-text-secondary leading-relaxed dark:text-dark-text-muted">
                        Memfasilitasi skema riset internal dan memotivasi keterlibatan dosen pada program dana hibah nasional kementerian.
                    </p>
                </div>

                <!-- Pillar 2: Pengabdian -->
                <div class="p-6 rounded-2xl bg-bg-surface border border-border-main shadow-subtle hover:shadow-card hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border">
                    <div class="w-10 h-10 rounded-xl bg-success-green-pale flex items-center justify-center text-success-green mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <h4 class="font-primary font-bold text-base text-text-main mb-2">Pengabdian Masyarakat</h4>
                    <p class="font-body text-xs text-text-secondary leading-relaxed dark:text-dark-text-muted">
                        Menerapkan hasil-hasil keilmuan secara langsung di lapangan untuk memecahkan hambatan sosial dan UMKM di desa binaan.
                    </p>
                </div>

                <!-- Pillar 3: Publikasi -->
                <div class="p-6 rounded-2xl bg-bg-surface border border-border-main shadow-subtle hover:shadow-card hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border">
                    <div class="w-10 h-10 rounded-xl bg-sky-accent flex items-center justify-center text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h4 class="font-primary font-bold text-base text-text-main mb-2">Publikasi Ilmiah</h4>
                    <p class="font-body text-xs text-text-secondary leading-relaxed dark:text-dark-text-muted">
                        Mendukung percepatan penulisan luaran artikel di OJS terindeks SINTA maupun reputasi internasional (Scopus).
                    </p>
                </div>

                <!-- Pillar 4: Kolaborasi -->
                <div class="p-6 rounded-2xl bg-bg-surface border border-border-main shadow-subtle hover:shadow-card hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border">
                    <div class="w-10 h-10 rounded-xl bg-alert-red-pale flex items-center justify-center text-alert-red mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    </div>
                    <h4 class="font-primary font-bold text-base text-text-main mb-2">Kolaborasi & Inovasi</h4>
                    <p class="font-body text-xs text-text-secondary leading-relaxed dark:text-dark-text-muted">
                        Membangun relasi eksternal berkesinambungan antara sivitas akademika dengan lembaga nasional dan internasional.
                    </p>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- Section 5: Section Pengumuman -->
<section id="pengumuman" class="py-20 lg:py-28 bg-bg-alt border-y border-border-main/50 dark:bg-dark-surface-soft/40 dark:border-dark-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ selectedAnn: null }">
        
        <!-- Header Text -->
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-12">
            <div>
                <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Papan Informasi</span>
                <h2 class="font-primary font-extrabold text-3xl sm:text-4xl text-text-main mt-2 leading-none">Pengumuman & Agenda</h2>
                <div class="h-1.5 w-16 bg-alert-red mt-4 rounded-full"></div>
            </div>
            <p class="font-body text-sm text-text-secondary md:max-w-md mt-4 md:mt-0 dark:text-dark-text-muted">
                Dapatkan kabar terkini, rilis kebijakan, agenda sosialisasi hibah, serta jadwal pelaporan penelitian & pengmas di sini.
            </p>
        </div>

        <!-- Grid Cards Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($announcements as $ann)
            <div class="p-6 rounded-2xl bg-bg-surface border-l-4 border-alert-red border-y border-r border-border-main shadow-subtle hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between dark:bg-dark-surface dark:border-dark-border dark:border-l-dark-red-accent relative overflow-hidden">
                <div>
                    <!-- Badge Header -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs text-text-secondary dark:text-dark-text-muted flex items-center">
                            <!-- Clock Icon -->
                            <svg class="w-4 h-4 mr-1.5 text-text-disabled" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            {{ $ann['date'] }}
                        </span>
                        
                        <!-- Dynamic Status Badge -->
                        @if($ann['category_type'] == 'important')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider bg-alert-red-pale text-alert-red dark:bg-dark-red-accent/15 dark:text-dark-red-accent">PENTING</span>
                        @elseif($ann['category_type'] == 'hibah')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider bg-gold-accent/15 text-gold-accent">JURNAL</span>
                        @elseif($ann['category_type'] == 'baru')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent">WORKSHOP</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider bg-success-green-pale text-success-green">UMUM</span>
                        @endif
                    </div>

                    <h3 class="font-primary font-bold text-lg text-text-main leading-snug hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150">
                        {{ $ann['title'] }}
                    </h3>
                    
                    <p class="font-body text-xs text-text-secondary leading-relaxed mt-3 line-clamp-3 dark:text-dark-text-muted">
                        {{ $ann['summary'] }}
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-border-main/50 flex items-center justify-between dark:border-dark-border">
                    <span class="text-[10px] font-semibold text-text-disabled uppercase">Dokumen: {{ $ann['file_size'] }}</span>
                    <!-- Action trigger trigger -->
                    <button @click="selectedAnn = {{ json_encode($ann) }}" 
                            class="text-xs font-bold text-mahardika-navy hover:text-mahardika-navy-soft dark:text-navy-accent dark:hover:text-white flex items-center space-x-1 hover:underline">
                        <span>Baca Selengkapnya</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Announcement Detail Modal (Controlled by Alpine.js) -->
        <div x-show="selectedAnn" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="selectedAnn = null"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="selectedAnn"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="relative transform overflow-hidden rounded-3xl bg-bg-surface border border-border-main p-6 sm:p-8 text-left shadow-floating transition-all duration-300 w-full max-w-2xl dark:bg-dark-surface dark:border-dark-border">
                    
                    <!-- Close button -->
                    <button @click="selectedAnn = null" class="absolute top-4 right-4 p-2 rounded-xl text-text-disabled hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft hover:text-text-main transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    <!-- Details Header -->
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="px-2.5 py-1 rounded bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent font-primary font-bold text-[10px] uppercase tracking-wider" x-text="selectedAnn ? selectedAnn.category : ''"></span>
                        <span class="text-xs text-text-secondary dark:text-dark-text-muted" x-text="selectedAnn ? selectedAnn.date : ''"></span>
                    </div>

                    <h3 class="font-primary font-extrabold text-xl sm:text-2xl text-text-main leading-tight mb-4" x-text="selectedAnn ? selectedAnn.title : ''"></h3>
                    
                    <div class="h-px bg-border-main dark:bg-dark-border my-4"></div>

                    <!-- Full Details Body -->
                    <div class="space-y-4 text-sm font-body text-text-secondary leading-relaxed dark:text-dark-text-muted">
                        <p x-text="selectedAnn ? selectedAnn.summary : ''"></p>
                        <p>Diharapkan kepada Bapak/Ibu Dosen di lingkungan Universitas Insan Mahardika untuk dapat mempersiapkan segala kelengkapan dokumen pengusulan sebaik mungkin sesuai dengan petunjuk teknis pelaksanaan dan sistematika penulisan proposal yang berlaku.</p>
                        <div class="p-4 rounded-xl bg-mahardika-navy-pale dark:bg-dark-surface-soft/50 border border-mahardika-navy/10 dark:border-dark-border flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-white dark:bg-dark-surface flex items-center justify-center text-alert-red shadow-subtle">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6l-4-4H9z" /><path d="M5 6a2 2 0 00-2 2v8a2 2 0 002 2h4a2 2 0 002-2v-1H5V6z" /></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-text-main leading-tight">Panduan_Pelaksanaan_Hibah_2026.pdf</span>
                                    <span class="text-[10px] text-text-secondary dark:text-dark-text-muted" x-text="selectedAnn ? 'Ukuran Berkas: ' + selectedAnn.file_size : ''"></span>
                                </div>
                            </div>
                            <!-- Mock Download Trigger with click tracking -->
                            <a href="#" @click.prevent="alert('Berkas sedang diunduh (Simulasi)')" class="px-3.5 py-1.5 rounded-lg bg-mahardika-navy text-white text-xs font-semibold hover:bg-mahardika-navy-soft dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft flex items-center space-x-1 transition-colors duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                <span>Unduh</span>
                            </a>
                        </div>
                    </div>

                    <!-- Close buttons -->
                    <div class="mt-8 pt-4 border-t border-border-main dark:border-dark-border flex justify-end space-x-3">
                        <button @click="selectedAnn = null" class="px-5 py-2.5 rounded-xl border border-border-main text-xs font-bold text-text-main hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors duration-150">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>

<!-- Section 6: Section Portal Penelitian & Pengmas -->
<section id="portal" class="py-20 lg:py-28 bg-bg-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Text -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Portal Ekosistem</span>
            <h2 class="font-primary font-extrabold text-3xl sm:text-4xl text-text-main mt-2 leading-tight">
                Integrasi Layanan Riset & Pemberdayaan Masyarakat
            </h2>
            <div class="h-1.5 w-16 bg-success-green mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Portal 1: Penelitian -->
            <div class="p-8 rounded-3xl bg-bg-surface border border-border-main shadow-card flex flex-col justify-between hover:shadow-floating hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border group">
                <div class="space-y-6">
                    <div class="w-14 h-14 rounded-2xl bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent flex items-center justify-center shadow-subtle group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    </div>
                    <div class="space-y-2">
                        <span class="text-xs uppercase font-extrabold tracking-widest text-mahardika-navy dark:text-navy-accent">{{ $portals['penelitian']['domain'] }}</span>
                        <h3 class="font-primary font-bold text-2xl text-text-main">{{ $portals['penelitian']['title'] }}</h3>
                        <p class="font-body text-sm text-text-secondary leading-relaxed dark:text-dark-text-muted">
                            {{ $portals['penelitian']['description'] }}
                        </p>
                    </div>
                </div>
                <!-- Action Button Actions -->
                <div class="mt-8 pt-6 border-t border-border-main dark:border-dark-border flex items-center justify-between">
                    <span class="text-xs text-text-secondary font-semibold dark:text-dark-text-muted flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-success-green mr-2 animate-pulse"></span> Sistem Aktif
                    </span>
                    <a href="{{ $portals['penelitian']['link'] }}" target="_blank" class="px-5  py-2.5 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle flex items-center space-x-1.5 transition-all duration-200 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft">
                        <span>Buka Sistem Penelitian</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 00-2 2h14a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>

            <!-- Portal 2: Pengabdian -->
            <div class="p-8 rounded-3xl bg-bg-surface border border-border-main shadow-card flex flex-col justify-between hover:shadow-floating hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border group">
                <div class="space-y-6">
                    <div class="w-14 h-14 rounded-2xl bg-success-green-pale text-success-green flex items-center justify-center shadow-subtle group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div class="space-y-2">
                        <span class="text-xs uppercase font-extrabold tracking-widest text-success-green">{{ $portals['pengmas']['domain'] }}</span>
                        <h3 class="font-primary font-bold text-2xl text-text-main">{{ $portals['pengmas']['title'] }}</h3>
                        <p class="font-body text-sm text-text-secondary leading-relaxed dark:text-dark-text-muted">
                            {{ $portals['pengmas']['description'] }}
                        </p>
                    </div>
                </div>
                <!-- Action Button Actions -->
                <div class="mt-8 pt-6 border-t border-border-main dark:border-dark-border flex items-center justify-between">
                    <span class="text-xs text-text-secondary font-semibold dark:text-dark-text-muted flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-success-green mr-2 animate-pulse"></span> Sistem Aktif
                    </span>
                    <a href="{{ $portals['pengmas']['link'] }}" target="_blank" class="px-5 py-2.5 rounded-xl font-primary font-bold text-xs bg-success-green text-white hover:bg-success-green-pale hover:text-success-green dark:hover:bg-success-green-pale dark:hover:text-success-green shadow-subtle flex items-center space-x-1.5 transition-all duration-200">
                        <span>Buka Sistem Pengmas</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 00-2 2h14a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Section 7: Section Direktori Dosen -->
<section id="dosen" class="py-20 lg:py-28 bg-bg-alt border-y border-border-main/50 dark:bg-dark-surface-soft/40 dark:border-dark-border" x-data="{ search: '', facultyFilter: 'semua' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header text and interactive client-side Search Inputs -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-12 gap-6">
            <div>
                <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Direktori Akademik</span>
                <h2 class="font-primary font-extrabold text-3xl sm:text-4xl text-text-main mt-2 leading-none">Dosen & Bidang Keahlian</h2>
                <div class="h-1.5 w-16 bg-gold-accent mt-4 rounded-full"></div>
            </div>
            
            <!-- Filters & Search Form Inputs Wrapper -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full lg:max-w-xl">
                <!-- Search input box -->
                <div class="relative flex-1">
                    <input type="text" 
                           x-model="search"
                           placeholder="Cari nama dosen atau bidang keahlian..." 
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-border-main bg-bg-surface focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:bg-dark-surface dark:border-dark-border dark:focus:ring-navy-accent/25 text-sm">
                    <svg class="w-5 h-5 text-text-disabled absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <!-- Selection dropdown filter -->
                <div class="relative">
                    <select x-model="facultyFilter" 
                            class="w-full sm:w-48 pl-3 pr-8 py-3 rounded-xl border border-border-main bg-bg-surface dark:bg-dark-surface dark:border-dark-border text-sm focus:outline-none appearance-none cursor-pointer">
                        <option value="semua">Semua Fakultas</option>
                        <option value="Fakultas Ilmu Kesehatan">Kesehatan</option>
                        <option value="Fakultas Ilmu Komputer">Komputer</option>
                        <option value="Fakultas Farmasi">Farmasi</option>
                        <option value="Fakultas Teknik">Teknik</option>
                        <option value="Fakultas Keguruan & Ilmu Pendidikan">Keguruan</option>
                        <option value="Fakultas Ekonomi & Bisnis">Ekonomi</option>
                    </select>
                    <svg class="w-4 h-4 text-text-disabled absolute right-3 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        </div>

        <!-- Lecturers dynamic cards list container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($lecturers as $lec)
            <div x-show="(search === '' || '{{ strtolower($lec['name']) }}'.includes(search.toLowerCase()) || '{{ strtolower($lec['expertise']) }}'.includes(search.toLowerCase())) && (facultyFilter === 'semua' || '{{ $lec['faculty'] }}' === facultyFilter)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="p-6 rounded-2xl bg-bg-surface border border-border-main shadow-subtle hover:shadow-card hover:-translate-y-1 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border flex flex-col justify-between group">
                
                <div class="space-y-4">
                    <!-- Lecturer header information -->
                    <div class="flex items-center space-x-4">
                        <div class="relative w-14 h-14 rounded-full overflow-hidden border-2 border-mahardika-navy/10 group-hover:border-mahardika-navy/30 transition-colors duration-200 dark:border-dark-border">
                            <img src="{{ $lec['avatar'] }}" alt="{{ $lec['name'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-primary font-bold text-sm text-text-main leading-tight group-hover:text-mahardika-navy dark:group-hover:text-navy-accent transition-colors duration-150">{{ $lec['name'] }}</h4>
                            <span class="text-[10px] text-text-secondary dark:text-dark-text-muted mt-0.5 font-semibold">NIDN: {{ $lec['nidn'] }}</span>
                        </div>
                    </div>

                    <div class="h-px bg-border-main dark:bg-dark-border"></div>

                    <!-- Details Area -->
                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-start">
                            <span class="font-bold text-text-disabled uppercase text-[8px] w-20 shrink-0 mt-0.5">Bidang</span>
                            <span class="text-text-main font-semibold leading-normal dark:text-white">{{ $lec['expertise'] }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-bold text-text-disabled uppercase text-[8px] w-20 shrink-0 mt-0.5">Fakultas</span>
                            <span class="text-text-secondary dark:text-dark-text-muted">{{ $lec['faculty'] }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-bold text-text-disabled uppercase text-[8px] w-20 shrink-0 mt-0.5">Prodi</span>
                            <span class="text-text-secondary dark:text-dark-text-muted">{{ $lec['study_program'] }}</span>
                        </div>
                    </div>

                    <!-- Publication details metrics row -->
                    <div class="flex items-center space-x-6 bg-mahardika-navy-pale dark:bg-dark-surface-soft p-2.5 rounded-xl text-xs font-semibold justify-around mt-4">
                        <div class="text-center">
                            <span class="block text-[9px] text-text-secondary dark:text-dark-text-muted uppercase">Publikasi</span>
                            <span class="font-extrabold text-mahardika-navy dark:text-navy-accent text-sm">{{ $lec['publications'] }}</span>
                        </div>
                        <div class="h-6 w-px bg-border-main dark:bg-dark-border"></div>
                        <div class="text-center">
                            <span class="block text-[9px] text-text-secondary dark:text-dark-text-muted uppercase">Sitasi</span>
                            <span class="font-extrabold text-gold-accent text-sm">{{ $lec['citations'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-border-main/50 dark:border-dark-border flex">
                    <a href="{{ route('landing.lecturer', ['slug' => $lec['slug']]) }}" class="w-full text-center py-2.5 rounded-xl text-xs font-semibold border border-border-main text-text-main hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150 shadow-subtle">
                        Lihat Profil Akademik
                    </a>
                </div>

            </div>
            @endforeach
        </div>

        <!-- Search fallback message shown if no cards are displayed -->
        <div x-show="document.querySelectorAll('#dosen .grid > div[style*=\'display: none\']').length === {{ count($lecturers) }}" 
             class="text-center py-16 bg-bg-surface border border-dashed border-border-main rounded-3xl mt-8 dark:bg-dark-surface dark:border-dark-border"
             style="display: none;">
            <svg class="w-12 h-12 text-text-disabled mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <h4 class="font-primary font-bold text-base text-text-main">Hasil tidak ditemukan</h4>
            <p class="font-body text-xs text-text-secondary mt-1 dark:text-dark-text-muted">Cobalah mencari dengan kata kunci keahlian atau nama yang lain.</p>
        </div>

    </div>
</section>

<!-- Section 8: Section Peraturan & Dokumen -->
<section id="peraturan" class="py-20 lg:py-28 bg-bg-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Text -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Repositori Legal</span>
            <h2 class="font-primary font-extrabold text-3xl sm:text-4xl text-text-main mt-2 leading-tight">
                Peraturan, Panduan & Dokumen SOP
            </h2>
            <div class="h-1.5 w-16 bg-alert-red mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Filter bar list for files -->
        <div x-data="{ docTab: 'semua' }" class="space-y-8">
            <div class="flex flex-wrap items-center justify-center gap-2">
                <button @click="docTab = 'semua'" :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent': docTab === 'semua', 'border border-border-main hover:bg-mahardika-navy-pale text-text-sub dark:border-dark-border dark:hover:bg-dark-surface-soft': docTab !== 'semua' }" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-150">Semua Dokumen</button>
                <button @click="docTab = 'Panduan Internal'" :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent': docTab === 'Panduan Internal', 'border border-border-main hover:bg-mahardika-navy-pale text-text-sub dark:border-dark-border dark:hover:bg-dark-surface-soft': docTab !== 'Panduan Internal' }" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-150">Panduan Internal</button>
                <button @click="docTab = 'Template'" :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent': docTab === 'Template', 'border border-border-main hover:bg-mahardika-navy-pale text-text-sub dark:border-dark-border dark:hover:bg-dark-surface-soft': docTab !== 'Template' }" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-150">Template Proposal</button>
                <button @click="docTab = 'SOP'" :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent': docTab === 'SOP', 'border border-border-main hover:bg-mahardika-navy-pale text-text-sub dark:border-dark-border dark:hover:bg-dark-surface-soft': docTab !== 'SOP' }" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-150">SOP Pelaksanaan</button>
                <button @click="docTab = 'Kemdikbudristek'" :class="{ 'bg-mahardika-navy text-white dark:bg-navy-accent': docTab === 'Kemdikbudristek', 'border border-border-main hover:bg-mahardika-navy-pale text-text-sub dark:border-dark-border dark:hover:bg-dark-surface-soft': docTab !== 'Kemdikbudristek' }" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-150">Hibah Nasional</button>
            </div>

            <!-- Documents Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($documents as $doc)
                <div x-show="docTab === 'semua' || '{{ $doc['category'] }}' === docTab"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="p-5 rounded-2xl bg-bg-surface border border-border-main shadow-subtle hover:shadow-card hover:-translate-y-0.5 transition-all duration-300 dark:bg-dark-surface dark:border-dark-border flex flex-col justify-between">
                    
                    <div class="space-y-4">
                        <div class="flex items-start justify-between">
                            <!-- Category Badge -->
                            <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-mahardika-navy-pale text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent">
                                {{ $doc['category'] }}
                            </span>
                            <!-- File format indicator -->
                            <span class="text-[9px] font-extrabold uppercase text-text-disabled">{{ $doc['type'] }} ({{ $doc['size'] }})</span>
                        </div>

                        <div class="flex items-start space-x-3 pt-1">
                            <!-- PDF or Word Icon Vector -->
                            <div class="w-10 h-10 rounded-lg bg-alert-red-pale dark:bg-dark-red-accent/10 flex items-center justify-center text-alert-red shrink-0">
                                @if($doc['type'] == 'PDF')
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                @else
                                    <svg class="w-6 h-6 text-mahardika-navy dark:text-navy-accent" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6l-4-4H9z" /></svg>
                                @endif
                            </div>
                            <h3 class="font-primary font-bold text-sm text-text-main leading-snug line-clamp-3 hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150">
                                {{ $doc['title'] }}
                            </h3>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-border-main/50 dark:border-dark-border flex">
                        <a href="#" @click.prevent="alert('Dokumen sedang diunduh (Simulasi)')" class="w-full py-2.5 rounded-xl font-primary font-bold text-xs bg-bg-alt text-mahardika-navy border border-border-main hover:bg-mahardika-navy-pale text-center flex items-center justify-center space-x-1.5 transition-all duration-150 dark:bg-dark-surface-soft dark:text-white dark:border-dark-border">
                            <!-- Download icon SVG -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            <span>Unduh Dokumen</span>
                        </a>
                    </div>

                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

<!-- Section 9: Section Jurnal Mahardika -->
<section id="jurnal" class="py-20 lg:py-28 bg-bg-alt border-y border-border-main/50 dark:bg-dark-surface-soft/40 dark:border-dark-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left Info Block: About JKM Journal -->
            <div class="lg:col-span-6 space-y-6">
                <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Publikasi Terakreditasi</span>
                <h2 class="font-primary font-extrabold text-3xl sm:text-4xl text-text-main leading-tight">
                    Jurnal Kesehatan Mahardika (JKM)
                </h2>
                <div class="h-1.5 w-16 bg-gold-accent rounded-full"></div>
                
                <p class="font-body text-sm text-text-secondary leading-relaxed dark:text-dark-text-muted">
                    Jurnal Kesehatan Mahardika (JKM) merupakan berkas penerbitan berkala ilmiah resmi yang dikelola langsung oleh LPPM Universitas Insan Mahardika. Jurnal ini ditujukan bagi penyebaran hasil penelitian teoretis maupun metodologis praktis di rumpun keperawatan, kebidanan, kesehatan masyarakat, farmasi, serta administrasi medis.
                </p>

                <p class="font-body text-sm text-text-secondary leading-relaxed dark:text-dark-text-muted">
                    JKM terbit dua kali setahun (Maret dan September), serta saat ini telah resmi terakreditasi oleh Direktorat Jenderal Pendidikan Tinggi, Riset, dan Teknologi Kemdikbudristek RI dengan peringkat **SINTA 3**.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 pt-2">
                    <a href="https://jurnal.mahardika.ac.id/index.php/jkm" target="_blank" class="w-full sm:w-auto px-6 py-3 rounded-xl font-primary font-bold text-center text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle transition-all duration-150 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft">
                        Kunjungi Jurnal JKM (OJS)
                    </a>
                    <a href="https://jurnal.mahardika.ac.id/index.php/jkm/about/submissions" target="_blank" class="w-full sm:w-auto px-6 py-3 rounded-xl font-primary font-bold text-center text-xs bg-white text-mahardika-navy border border-border-main hover:bg-mahardika-navy-pale shadow-subtle transition-all duration-150 dark:bg-dark-surface dark:text-white dark:border-dark-border dark:hover:bg-dark-surface-soft">
                        Panduan Penulisan Penulis
                    </a>
                </div>
            </div>

            <!-- Right Column: Call For Articles Poster Card -->
            <div class="lg:col-span-6">
                <!-- Gorgeous Call for Articles card -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-mahardika-navy via-mahardika-navy-soft to-[#1e4d8a] p-8 text-white shadow-floating dark:from-dark-surface dark:to-dark-surface-soft border border-white/10 dark:border-dark-border flex flex-col justify-between min-h-[380px] group">
                    <!-- Vector background decorations -->
                    <div class="absolute right-0 bottom-0 opacity-10 group-hover:scale-105 transition-transform duration-500 pointer-events-none">
                        <svg class="w-72 h-72 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold tracking-wider bg-gold-accent/20 text-gold-accent uppercase border border-gold-accent/30">
                                Call For Articles
                            </span>
                            <span class="text-xs text-mahardika-navy-pale/70 uppercase font-semibold">Volume 8 Nomor 2</span>
                        </div>

                        <div class="space-y-3">
                            <h3 class="font-primary font-extrabold text-2xl sm:text-3xl text-white leading-tight">
                                Penerbitan Edisi Agustus 2026
                            </h3>
                            <p class="font-body text-xs text-mahardika-navy-pale/85 leading-relaxed max-w-md">
                                LPPM Universitas Insan Mahardika mengundang para dosen, mahasiswa pascasarjana, klinisi, dan peneliti kesehatan untuk menaruh naskah terbaiknya ke Jurnal JKM. 
                            </p>
                        </div>

                        <!-- Important deadlines checklist -->
                        <div class="space-y-2 bg-white/5 dark:bg-black/20 p-4 rounded-2xl border border-white/5 dark:border-dark-border">
                            <div class="flex items-center text-xs text-mahardika-navy-pale">
                                <span class="w-2 h-2 rounded-full bg-gold-accent mr-2.5"></span>
                                <span>Batas Akhir Penyerahan: <strong class="text-white">10 Juli 2026</strong></span>
                            </div>
                            <div class="flex items-center text-xs text-mahardika-navy-pale">
                                <span class="w-2 h-2 rounded-full bg-gold-accent mr-2.5"></span>
                                <span>Proses Desk Review & Peer-Review: <strong class="text-white">Juli 2026</strong></span>
                            </div>
                            <div class="flex items-center text-xs text-mahardika-navy-pale">
                                <span class="w-2 h-2 rounded-full bg-gold-accent mr-2.5"></span>
                                <span>Estimasi Penerbitan Online: <strong class="text-white">31 Agustus 2026</strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t border-white/10 dark:border-dark-border flex flex-col sm:flex-row items-center gap-3 relative z-10">
                        <a href="https://jurnal.mahardika.ac.id/index.php/jkm/about/submissions" target="_blank" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-primary font-bold text-center text-xs bg-gold-accent text-mahardika-navy hover:bg-amber-400 transition-colors duration-150">
                            Submit Artikel Sekarang
                        </a>
                        <span class="text-[10px] text-mahardika-navy-pale/60 uppercase text-center sm:text-left">Terindeks SINTA, Google Scholar, Garuda</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Section 10: Section Alur Layanan -->
<section id="alur" class="py-20 lg:py-28 bg-bg-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Text -->
        <div class="text-center max-w-3xl mx-auto mb-20">
            <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Prosedur Standard</span>
            <h2 class="font-primary font-extrabold text-3xl sm:text-4xl text-text-main mt-2 leading-tight">
                Alur Layanan Pengusulan Proposal
            </h2>
            <div class="h-1.5 w-16 bg-success-green mx-auto mt-4 rounded-full"></div>
            <p class="font-body text-sm text-text-secondary mt-4 dark:text-dark-text-muted">
                Langkah-langkah sistematis yang dijalankan untuk pengusulan hibah penelitian dan pengabdian masyarakat.
            </p>
        </div>

        <!-- Timeline Stepper (Horizontal on Desktop, Vertical on Mobile) -->
        <div class="relative">
            
            <!-- Horizontal Stepper (Hidden on Mobile) -->
            <div class="hidden lg:grid grid-cols-7 gap-4 relative z-10">
                @foreach($timeline as $step)
                <div class="flex flex-col items-center text-center group">
                    <!-- Circle step indicator -->
                    <div class="w-12 h-12 rounded-full border-4 border-mahardika-navy-pale bg-white flex items-center justify-center text-mahardika-navy group-hover:bg-mahardika-navy group-hover:text-white transition-all duration-300 shadow-card dark:border-dark-border dark:bg-dark-surface dark:text-navy-accent dark:group-hover:bg-navy-accent dark:group-hover:text-white relative">
                        <span class="font-primary font-extrabold text-xs tracking-tight">{{ $step['step'] }}</span>
                        
                        <!-- Connector lines except last one -->
                        @if(!$loop->last)
                            <div class="absolute left-12 top-4 w-28 h-1 bg-mahardika-navy-pale dark:bg-dark-border -z-10 group-hover:bg-mahardika-navy transition-colors duration-300"></div>
                        @endif
                    </div>
                    
                    <h4 class="font-primary font-bold text-xs text-text-main mt-6 group-hover:text-mahardika-navy dark:group-hover:text-navy-accent transition-colors duration-150 leading-tight">
                        {{ $step['title'] }}
                    </h4>
                    
                    <p class="font-body text-[10px] text-text-secondary leading-normal mt-2.5 max-w-[140px] dark:text-dark-text-muted">
                        {{ $step['description'] }}
                    </p>
                </div>
                @endforeach
            </div>

            <!-- Vertical Timeline Stepper (Hidden on Desktop) -->
            <div class="lg:hidden space-y-8 relative before:absolute before:left-6 before:top-2 before:bottom-2 before:w-0.5 before:bg-mahardika-navy-pale dark:before:bg-dark-border">
                @foreach($timeline as $step)
                <div class="flex items-start space-x-6 relative">
                    <!-- Step circle -->
                    <div class="w-12 h-12 rounded-full bg-bg-surface border-2 border-mahardika-navy text-mahardika-navy flex items-center justify-center shrink-0 shadow-subtle font-primary font-extrabold text-xs dark:bg-dark-surface dark:border-navy-accent dark:text-navy-accent">
                        {{ $step['step'] }}
                    </div>
                    <!-- Text contents -->
                    <div class="space-y-1 bg-bg-surface border border-border-main p-5 rounded-2xl shadow-subtle flex-1 dark:bg-dark-surface dark:border-dark-border">
                        <h4 class="font-primary font-bold text-sm text-text-main">{{ $step['title'] }}</h4>
                        <p class="font-body text-xs text-text-secondary leading-relaxed dark:text-dark-text-muted">
                            {{ $step['description'] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

    </div>
</section>

<!-- Section 11: Section Kontak -->
<section id="kontak" class="py-20 lg:py-28 bg-bg-alt border-t border-border-main/50 dark:bg-dark-surface-soft/40 dark:border-dark-border" x-data="{ 
    name: '', 
    email: '', 
    message: '', 
    success: false, 
    loading: false,
    submitForm() {
        this.loading = true;
        setTimeout(() => {
            this.loading = false;
            this.success = true;
            this.name = '';
            this.email = '';
            this.message = '';
            setTimeout(() => this.success = false, 4000);
        }, 1200);
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            
            <!-- Left Info column -->
            <div class="lg:col-span-5 space-y-8">
                <div>
                    <span class="font-primary font-bold text-xs uppercase tracking-widest text-mahardika-navy dark:text-navy-accent">Hubungi Kami</span>
                    <h2 class="font-primary font-extrabold text-3xl sm:text-4xl text-text-main mt-2 leading-none">Hubungi Kantor LPPM</h2>
                    <div class="h-1.5 w-16 bg-gold-accent mt-4 rounded-full"></div>
                </div>

                <p class="font-body text-sm text-text-secondary leading-relaxed dark:text-dark-text-muted">
                    Bila Bapak/Ibu Dosen memiliki pertanyaan seputar klasterisasi, tata cara login portal, review proposal, SOP pengabdian, maupun penyerahan luaran hibah, silakan hubungi tim sekretariat kami atau kunjungi sekretariat LPPM Mahardika.
                </p>

                <!-- Detailed Contact list -->
                <div class="space-y-4 font-body text-sm text-text-secondary dark:text-dark-text-muted">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-9 h-9 rounded-lg bg-mahardika-navy-pale dark:bg-dark-surface-soft flex items-center justify-center text-mahardika-navy dark:text-navy-accent shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-text-disabled uppercase font-bold tracking-wider">Sekretariat</span>
                            <span class="text-xs text-text-main leading-snug">Gedung Pelayanan Utama Kampus Universitas Insan Mahardika, Lantai 2, Jl. Mahardika Raya No. 45, Kota Cirebon, Jawa Barat.</span>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3.5">
                        <div class="w-9 h-9 rounded-lg bg-success-green-pale flex items-center justify-center text-success-green shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-text-disabled uppercase font-bold tracking-wider">Surat Elektronik</span>
                            <a href="mailto:lppm@mahardika.ac.id" class="text-xs text-mahardika-navy hover:underline dark:text-navy-accent font-semibold">lppm@mahardika.ac.id</a>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3.5">
                        <div class="w-9 h-9 rounded-lg bg-sky-accent flex items-center justify-center text-mahardika-navy dark:bg-dark-surface-soft dark:text-navy-accent shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-text-disabled uppercase font-bold tracking-wider">Telepon Kantor</span>
                            <a href="tel:0231456789" class="text-xs text-text-main font-semibold">(0231) 456-789</a>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Placeholder Frame -->
                <div class="rounded-3xl border border-border-main overflow-hidden shadow-card aspect-[4/3] bg-bg-surface relative dark:bg-dark-surface dark:border-dark-border">
                    <!-- Mock Map illustration -->
                    <div class="absolute inset-0 bg-sky-100/60 dark:bg-dark-surface-soft flex items-center justify-center">
                        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#000_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none dark:opacity-20"></div>
                        <div class="flex flex-col items-center space-y-3 p-4 text-center z-10">
                            <!-- Location Pin icon animation -->
                            <div class="w-12 h-12 rounded-full bg-alert-red-pale border-2 border-alert-red flex items-center justify-center text-alert-red animate-bounce">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="font-primary font-bold text-xs text-text-main leading-tight">Kampus Universitas Insan Mahardika</span>
                            <span class="text-[10px] text-text-secondary dark:text-dark-text-muted">Jl. Mahardika Raya No. 45, Cirebon</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form column -->
            <div class="lg:col-span-7">
                <div class="bg-bg-surface border border-border-main p-6 sm:p-8 rounded-3xl shadow-floating dark:bg-dark-surface dark:border-dark-border">
                    <h3 class="font-primary font-bold text-xl text-text-main mb-2">Formulir Pesan Cepat</h3>
                    <p class="font-body text-xs text-text-secondary mb-6 dark:text-dark-text-muted">Isi formulir berikut dan sekretariat kami akan membalas via email Anda dalam kurun waktu 1x24 jam kerja.</p>

                    <!-- Alert message success toast -->
                    <div x-show="success" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mb-6 p-4 rounded-xl bg-success-green-pale border border-success-green/20 text-success-green text-xs font-semibold flex items-center space-x-2"
                         style="display: none;">
                        <svg class="w-5 h-5 text-success-green shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Pesan Anda berhasil dikirim! Kami akan segera meninjau dan merespon kembali.</span>
                    </div>

                    <!-- Main Form Fields -->
                    <form @submit.prevent="submitForm()" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted mb-2">Nama Lengkap</label>
                                <input type="text" 
                                       x-model="name"
                                       required
                                       placeholder="Nama Lengkap & Gelar..."
                                       class="w-full px-4 py-3 rounded-xl border border-border-main bg-bg-surface text-xs focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:bg-dark-surface-soft dark:border-dark-border dark:focus:ring-navy-accent/25">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted mb-2">Alamat Email</label>
                                <input type="email" 
                                       x-model="email"
                                       required
                                       placeholder="nama@mahardika.ac.id..."
                                       class="w-full px-4 py-3 rounded-xl border border-border-main bg-bg-surface text-xs focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:bg-dark-surface-soft dark:border-dark-border dark:focus:ring-navy-accent/25">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted mb-2">Isi Pesan / Pertanyaan</label>
                            <textarea rows="5" 
                                      x-model="message"
                                      required
                                      placeholder="Tuliskan isi pesan atau pertanyaan Anda di sini secara detail..."
                                      class="w-full px-4 py-3 rounded-xl border border-border-main bg-bg-surface text-xs focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:bg-dark-surface-soft dark:border-dark-border dark:focus:ring-navy-accent/25 resize-none"></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                    :disabled="loading"
                                    class="w-full sm:w-auto px-6 py-3 rounded-xl font-primary font-bold text-xs bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft flex items-center justify-center space-x-2 transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                <!-- Loading Spinner icon SVG -->
                                <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="loading ? 'Mengirim...' : 'Kirim Pesan'">Kirim Pesan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Section 12: CTA Section bottom -->
<section class="py-16 bg-gradient-to-r from-mahardika-navy to-mahardika-navy-soft dark:from-dark-surface dark:to-dark-surface-soft border-t border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-15 pointer-events-none">
        <svg class="absolute -right-24 -top-24 w-96 h-96 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 22h20L12 2zm0 3.99L19.53 19H4.47L12 5.99z"/>
        </svg>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 text-center space-y-6 relative z-10">
        <h2 class="font-primary font-extrabold text-2xl sm:text-3xl text-white">Siap Mengajukan Proposal Penelitian atau Pengabdian?</h2>
        <p class="font-body text-xs sm:text-sm text-mahardika-navy-pale leading-relaxed max-w-2xl mx-auto">
            Akses sistem portal penelitian atau pengmas untuk memulai penyusunan usulan, pendaftaran reviewer, tracking kemajuan skema, dan upload berkas pendukung dalam satu dashboard modern.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
            <a href="https://penelitian.mahardika.ac.id" target="_blank" class="w-full sm:w-auto px-6 py-3 rounded-xl font-primary font-bold text-center text-xs bg-white text-mahardika-navy hover:bg-mahardika-navy-pale shadow-floating hover:scale-[1.01] active:scale-[0.99] transition-all duration-200">
                Masuk Portal Penelitian
            </a>
            <a href="https://pengmas.mahardika.ac.id" target="_blank" class="w-full sm:w-auto px-6 py-3 rounded-xl font-primary font-bold text-center text-xs bg-success-green text-white hover:bg-emerald-700 shadow-floating hover:scale-[1.01] active:scale-[0.99] transition-all duration-200">
                Masuk Portal Pengabdian
            </a>
        </div>
    </div>
</section>

@endsection
