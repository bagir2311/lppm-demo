<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - CMS Admin LPPM Mahardika</title>
    
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js & Tailwind CSS Assets (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('mahardika-logo.png') }}">
</head>
<body class="font-body bg-gray-50 text-text-main dark:bg-[#0b1220] dark:text-text-main transition-colors duration-200">

    <div class="flex min-h-screen overflow-hidden">
        
        <!-- Sidebar Navigation (Desktop & Mobile Panel overlay) -->
        <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" 
               class="fixed inset-y-0 left-0 z-40 w-64 bg-mahardika-navy text-white flex flex-col justify-between border-r border-white/10 shadow-floating transition-transform duration-300 lg:translate-x-0 lg:static lg:z-0 shrink-0 dark:bg-[#00183b] dark:border-dark-border">
            
            <div class="flex flex-col flex-1">
                <!-- Sidebar Header -->
                <div class="h-20 flex items-center px-6 border-b border-white/10 dark:border-dark-border">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-primary font-extrabold text-sm tracking-wide">CMS MAHARDIKA</span>
                            <span class="text-[9px] text-mahardika-navy-pale uppercase tracking-widest leading-none mt-0.5">Superadmin Panel</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation items list -->
                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- E-Catalog Penelitian -->
                    <a href="{{ route('admin.penelitian') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.penelitian') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        <span>Katalog Penelitian</span>
                    </a>

                    <!-- E-Catalog Pengabdian -->
                    <a href="{{ route('admin.pengabdian') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.pengabdian') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11.4M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Katalog Pengabdian</span>
                    </a>

                    <!-- Verifikasi Berkas LPJ -->
                    <a href="{{ route('admin.berkas.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.berkas.*') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z" /></svg>
                        <span>Verifikasi Berkas LPJ</span>
                    </a>

                    <!-- Layanan Persuratan -->
                    <a href="{{ route('admin.surat.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.surat.*') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <span>Layanan Persuratan</span>
                    </a>

                    <!-- Verifikasi HKI -->
                    <a href="{{ route('admin.hki.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.hki.*') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                        <span>Verifikasi HKI</span>
                    </a>
                    
                    <!-- Announcements -->
                    <a href="{{ route('admin.announcements') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.announcements') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span>Pengumuman</span>
                    </a>

                    <!-- Lecturers Directory -->
                    <a href="{{ route('admin.lecturers') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.lecturers') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span>Direktori Dosen</span>
                    </a>

                    <!-- Manajemen Akun Pengguna & Staf -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span>Manajemen Akun</span>
                    </a>

                    <!-- Documents Regulations -->
                    <a href="{{ route('admin.documents') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.documents') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.9 4.9A2 2 0 0119 9.83V19a2 2 0 01-2 2z" /></svg>
                        <span>Peraturan & SOP</span>
                    </a>

                    <!-- Jurnal OJS Links -->
                    <a href="{{ route('admin.journals') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.journals') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <span>Jurnal Kesehatan</span>
                    </a>

                    <!-- Landing Variables Settings -->
                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center space-x-3.5 px-4 py-3.5 rounded-xl text-xs font-semibold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-white/10 text-white dark:bg-navy-accent dark:text-white' : 'text-mahardika-navy-pale hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Pengaturan LPPM</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer / Auth actions -->
            <div class="p-4 border-t border-white/10 dark:border-dark-border space-y-2">
                <!-- Return Main Site -->
                <a href="{{ route('landing.index') }}" class="w-full flex items-center justify-center space-x-2 py-2 px-4 rounded-xl border border-white/20 hover:bg-white/10 text-xs font-semibold transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span>Situs Utama</span>
                </a>
                
                <!-- Logout -->
                <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 py-2 px-4 rounded-xl bg-alert-red hover:bg-[#8f0606] text-xs font-bold transition-all duration-150 text-white cursor-pointer shadow-subtle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span>Keluar Admin</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace Panel -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            <!-- Top Workspace Header -->
            <header class="h-20 bg-white border-b border-border-main flex items-center justify-between px-6 sm:px-8 shrink-0 dark:bg-dark-surface dark:border-dark-border transition-colors duration-200">
                
                <!-- Sidebar Toggle and Page Title -->
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="p-2 rounded-lg border border-border-main text-text-main lg:hidden hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors duration-150"
                            aria-label="Toggle Navigation Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="font-primary font-bold text-lg text-text-main">@yield('page-title')</h2>
                </div>

                <!-- Admin Action details -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode" 
                            class="p-2.5 rounded-xl border border-border-main bg-bg-alt text-text-sub hover:text-mahardika-navy dark:hover:text-navy-accent dark:bg-dark-surface-soft dark:border-dark-border transition-all duration-200"
                            aria-label="Toggle Dark Mode Workspace">
                        <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                        <svg x-show="!darkMode" class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <div class="h-6 w-px bg-border-main dark:bg-dark-border"></div>

                    <!-- Administrator Avatar badge -->
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-mahardika-navy dark:bg-navy-accent flex items-center justify-center text-white font-primary font-bold text-xs shadow-card">
                            SA
                        </div>
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-text-main leading-none">Super Administrator</span>
                            <span class="text-[10px] text-text-secondary dark:text-dark-text-muted mt-1">superadmin@mahardika.ac.id</span>
                        </div>
                    </div>
                </div>

            </header>

            <!-- Main Dynamic Workspace View -->
            <main class="flex-1 p-6 sm:p-8 lg:p-10 space-y-8 max-w-7xl w-full mx-auto">
                
                <!-- Dynamic Session Feedback alerts -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="p-4 rounded-2xl bg-success-green-pale border border-success-green/20 text-success-green font-semibold text-xs flex items-center justify-between shadow-subtle">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-success-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-success-green hover:opacity-80">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="p-4 rounded-2xl bg-alert-red-pale border border-alert-red/20 text-alert-red font-semibold text-xs flex items-center justify-between shadow-subtle">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-alert-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-alert-red hover:opacity-80">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
