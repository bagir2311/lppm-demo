@php
    $authUser = auth()->user();
    $isLoggedIn = auth()->check() || session()->has('admin_logged_in') || session()->has('user_logged_in');

    $userRole = 'guest';
    if (auth()->check()) {
        $userRole = strtolower($authUser->role ?? 'user');
    } elseif (session()->has('admin_logged_in')) {
        $userRole = 'admin';
    } elseif (session()->has('user_role')) {
        $userRole = strtolower(session('user_role'));
    }

    $userName = auth()->check() 
        ? ($authUser->name ?? 'Pengguna') 
        : (session('user_name') ?? (session('admin_logged_in') ? 'Administrator' : 'Dosen'));
    $userEmail = auth()->check() ? ($authUser->email ?? '') : (session('user_email') ?? '');

    if ($userRole === 'dosen' || $userRole === 'user') {
        $dashboardUrl = route('dosen.dashboard');
        $roleLabel = 'Dosen';
        $roleInitial = 'D';
        $roleBadgeBg = 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300';
    } elseif ($userRole === 'reviewer') {
        $dashboardUrl = route('reviewer.dashboard');
        $roleLabel = 'Reviewer';
        $roleInitial = 'R';
        $roleBadgeBg = 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300';
    } elseif (in_array($userRole, ['admin', 'staf', 'staff', 'admin_lppm'])) {
        $dashboardUrl = route('admin.dashboard');
        $roleLabel = in_array($userRole, ['admin', 'admin_lppm']) ? 'Administrator' : 'Staf LPPM';
        $roleInitial = 'A';
        $roleBadgeBg = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300';
    } else {
        $dashboardUrl = route('dosen.dashboard');
        $roleLabel = 'Pengguna';
        $roleInitial = 'U';
        $roleBadgeBg = 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
    }
@endphp
<nav x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false, 
        activeSection: (window.location.hash ? window.location.hash.substring(1) : 'hero'),
        isClicking: false,
        setActive(section) {
            this.activeSection = section;
            this.isClicking = true;
            setTimeout(() => { this.isClicking = false; }, 800);
        },
        updateActiveFromScroll() {
            if (this.isClicking) return;
            if (!{{ request()->routeIs('landing.index') ? 'true' : 'false' }}) return;
            const sections = ['hero', 'portal', 'dosen', 'pengumuman', 'jurnal', 'alur', 'kontak'];
            const scrollPosition = window.scrollY + 140;
            if ((window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 60)) {
                this.activeSection = 'kontak';
                return;
            }
            for (let i = sections.length - 1; i >= 0; i--) {
                const el = document.getElementById(sections[i]);
                if (el && el.offsetTop <= scrollPosition) {
                    this.activeSection = sections[i];
                    break;
                }
            }
        }
     }"
     x-init="
        window.addEventListener('scroll', () => {
            scrolled = window.scrollY > 10;
            updateActiveFromScroll();
        });
        window.addEventListener('hashchange', () => {
            if (window.location.hash) {
                activeSection = window.location.hash.substring(1);
            }
        });
        if (window.location.hash) {
            activeSection = window.location.hash.substring(1);
        } else {
            updateActiveFromScroll();
        }
     "
     :class="{ 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-md shadow-sm': scrolled, 'bg-white dark:bg-gray-900': !scrolled }"
     class="fixed top-0 left-0 right-0 z-50 w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 transition-colors duration-200">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- 1. Brand Logo --}}
            <a href="{{ request()->routeIs('landing.index') ? '#hero' : route('landing.index') . '#hero' }}" 
               @click="setActive('hero')"
               class="flex items-center space-x-3 group shrink-0">
                <div class="w-10 h-10 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('mahardika-logo.png') }}" alt="Logo Mahardika" class="w-full h-full object-contain drop-shadow-sm">
                </div>
                <div class="flex flex-col">
                    <span class="font-primary font-extrabold text-base lg:text-lg tracking-tight text-[#00295f] dark:text-white leading-none">LPPM MAHARDIKA</span>
                    <span class="font-body text-[10px] text-gray-800 dark:text-white tracking-wider uppercase font-semibold mt-0.5">Univ. Insan Mahardika</span>
                </div>
            </a>

            {{-- 2. Desktop Navigation Menu --}}
            <div class="hidden xl:flex items-center space-x-6 text-sm">
                {{-- Beranda --}}
                <a href="{{ request()->routeIs('landing.index') ? '#hero' : route('landing.index') . '#hero' }}" 
                   @click="setActive('hero')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'hero' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Beranda
                </a>

                {{-- Portal Sistem --}}
                <a href="{{ request()->routeIs('landing.index') ? '#portal' : route('landing.index') . '#portal' }}" 
                   @click="setActive('portal')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'portal' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Portal Sistem
                </a>

                {{-- Dosen --}}
                <a href="{{ request()->routeIs('landing.index') ? '#dosen' : route('landing.index') . '#dosen' }}" 
                   @click="setActive('dosen')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'dosen' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="{{ request()->routeIs('landing.lecturer') ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent' }}"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Dosen
                </a>

                {{-- Pengumuman --}}
                <a href="{{ request()->routeIs('landing.index') ? '#pengumuman' : route('landing.index') . '#pengumuman' }}" 
                   @click="setActive('pengumuman')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'pengumuman' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Pengumuman
                </a>

                {{-- Jurnal --}}
                <a href="{{ request()->routeIs('landing.index') ? '#jurnal' : route('landing.index') . '#jurnal' }}" 
                   @click="setActive('jurnal')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'jurnal' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Jurnal
                </a>

                {{-- Alur Pengajuan --}}
                <a href="{{ request()->routeIs('landing.index') ? '#alur' : route('landing.index') . '#alur' }}" 
                   @click="setActive('alur')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'alur' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Alur
                </a>

                {{-- Kontak --}}
                <a href="{{ request()->routeIs('landing.index') ? '#kontak' : route('landing.index') . '#kontak' }}" 
                   @click="setActive('kontak')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'kontak' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Kontak
                </a>
            </div>

            {{-- 3. Header Action Items (Paling Kanan) --}}
            <div class="hidden lg:flex items-center space-x-3 shrink-0">
                {{-- Dark Mode Toggle --}}
                <button @click="darkMode = !darkMode" 
                        type="button"
                        class="p-2 rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-[#00295f] dark:hover:text-blue-400 transition-colors duration-200"
                        aria-label="Toggle Dark Mode">
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                {{-- TOMBOL AKSI / AUTENTIKASI --}}
                @if($isLoggedIn)
                    <div class="relative" x-data="{ openProfile: false }">
                        <button @click="openProfile = !openProfile" @click.outside="openProfile = false" 
                                class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 px-3 py-2 rounded-lg transition text-xs font-semibold text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-700 shadow-xs">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[10px] {{ $roleBadgeBg }}">
                                {{ $roleInitial }}
                            </div>
                            <span class="max-w-[110px] truncate leading-tight">{{ $userName }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="openProfile ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openProfile" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             style="display: none;" 
                             class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl py-2 z-50">
                            
                            <div class="px-4 py-2.5 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $userName }}</p>
                                @if($userEmail)
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $userEmail }}</p>
                                @endif
                                <div class="mt-1">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold {{ $roleBadgeBg }}">
                                        {{ $roleLabel }}
                                    </span>
                                </div>
                            </div>

                            <div class="py-1">
                                <a href="{{ $dashboardUrl }}" class="flex items-center gap-2 px-4 py-2 text-xs text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-700 font-medium">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    Dasbor Saya
                                </a>
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700 pt-1">
                                <a href="{{ route('logout') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-gray-700 font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar (Logout)
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Tombol Login Netral (Admin, Staf, & Dosen) --}}
                    <a href="{{ route('login') }}" 
                       class="bg-gray-100 text-[#00295f] font-medium px-4 py-2 rounded-lg transition-colors duration-200 hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 text-xs">
                        Login
                    </a>
                @endif

                {{-- Tombol Akses Portal (Tombol Biru di Paling Kanan) --}}
                <a href="{{ request()->routeIs('landing.index') ? '#portal' : route('portal.index') }}" 
                   @if(request()->routeIs('landing.index')) @click="setActive('portal')" @endif
                   class="px-4 py-2 rounded-lg text-xs font-medium bg-[#00295f] text-white hover:bg-[#001c4a] dark:bg-blue-600 dark:hover:bg-blue-500 shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all duration-150">
                    Akses Portal
                </a>
            </div>

            {{-- 4. Mobile Menu & Dark Mode Button (< 1280px) --}}
            <div class="flex items-center space-x-2 xl:hidden">
                <button @click="darkMode = !darkMode" 
                        type="button"
                        class="p-2 rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300"
                        aria-label="Toggle Dark Mode Mobile">
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button"
                        class="p-2 rounded-lg border border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        aria-label="Buka Menu Navigasi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- 5. Mobile Drawer Menu --}}
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="xl:hidden w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-lg px-4 pt-3 pb-6 space-y-1"
         style="display: none;">
        
        <a href="{{ request()->routeIs('landing.index') ? '#hero' : route('landing.index') . '#hero' }}" 
           @click="setActive('hero'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'hero' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Beranda
        </a>

        <a href="{{ request()->routeIs('landing.index') ? '#portal' : route('landing.index') . '#portal' }}" 
           @click="setActive('portal'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'portal' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Portal Sistem
        </a>

        <a href="{{ request()->routeIs('landing.index') ? '#dosen' : route('landing.index') . '#dosen' }}" 
           @click="setActive('dosen'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'dosen' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="{{ request()->routeIs('landing.lecturer') ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Dosen
        </a>

        <a href="{{ request()->routeIs('landing.index') ? '#pengumuman' : route('landing.index') . '#pengumuman' }}" 
           @click="setActive('pengumuman'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'pengumuman' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Pengumuman
        </a>

        <a href="{{ request()->routeIs('landing.index') ? '#jurnal' : route('landing.index') . '#jurnal' }}" 
           @click="setActive('jurnal'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'jurnal' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Jurnal
        </a>

        <a href="{{ request()->routeIs('landing.index') ? '#alur' : route('landing.index') . '#alur' }}" 
           @click="setActive('alur'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'alur' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Alur Pengajuan
        </a>

        <a href="{{ request()->routeIs('landing.index') ? '#kontak' : route('landing.index') . '#kontak' }}" 
           @click="setActive('kontak'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'kontak' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Kontak
        </a>

        <div class="pt-3 border-t border-gray-200 dark:border-gray-800 flex flex-col gap-2">
            @if($isLoggedIn)
                <div class="p-3 bg-gray-50 dark:bg-gray-800/80 rounded-xl flex items-center gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs {{ $roleBadgeBg }}">
                        {{ $roleInitial }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $userName }}</p>
                        @if($userEmail)
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">{{ $userEmail }}</p>
                        @endif
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[9px] font-semibold {{ $roleBadgeBg }}">
                            {{ $roleLabel }}
                        </span>
                    </div>
                </div>
                <a href="{{ $dashboardUrl }}" 
                   @click="mobileMenuOpen = false" 
                   class="w-full text-center py-2.5 px-4 rounded-xl text-xs font-semibold bg-blue-50 text-[#00295f] dark:bg-blue-900/40 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 transition-colors">
                    Dasbor Saya
                </a>
                <a href="{{ route('logout') }}" 
                   @click="mobileMenuOpen = false" 
                   class="w-full text-center py-2.5 px-4 rounded-xl text-xs font-semibold bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                    Keluar (Logout)
                </a>
            @else
                <a href="{{ route('login') }}" 
                   @click="mobileMenuOpen = false" 
                   class="w-full text-center py-2.5 px-4 rounded-xl text-xs font-medium bg-gray-100 text-[#00295f] hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 transition-colors">
                    Login
                </a>
            @endif

            <a href="{{ request()->routeIs('landing.index') ? '#portal' : route('portal.index') }}" 
               @click="@if(request()->routeIs('landing.index')) setActive('portal'); @endif mobileMenuOpen = false" 
               class="w-full text-center py-2.5 px-4 rounded-xl text-xs font-semibold bg-[#00295f] text-white hover:bg-[#001c4a] dark:bg-blue-600 shadow-sm transition-all duration-150">
                Akses Portal
            </a>
        </div>
    </div>
</nav>