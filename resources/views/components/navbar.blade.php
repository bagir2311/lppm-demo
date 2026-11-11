@php
    $isLoggedIn = auth()->check() || session()->has('user_logged_in') || session()->has('admin_logged_in') || (session()->has('user_role') && session('user_role') !== 'guest');
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
            const sections = ['hero', 'profil', 'pengumuman', 'dosen', 'peraturan', 'jurnal', 'kontak'];
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

                {{-- Katalog --}}
                <a href="{{ route('katalog') }}" 
                   class="{{ request()->routeIs('katalog') || request()->routeIs('catalog.*') ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent' }} py-1 transition-colors duration-200 border-b-2">
                    Katalog
                </a>

                @if($isLoggedIn)
                {{-- Hibah --}}
                <a href="{{ route('hibah') }}" 
                   class="{{ request()->routeIs('hibah') ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent' }} py-1 transition-colors duration-200 border-b-2">
                    Hibah
                </a>

                {{-- Surat --}}
                <a href="{{ route('surat.index') }}" 
                   class="{{ request()->routeIs('surat.*') ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent' }} py-1 transition-colors duration-200 border-b-2">
                    Surat
                </a>

                {{-- Laporan --}}
                <a href="{{ route('berkas.index') }}" 
                   class="{{ request()->routeIs('berkas.*') ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent' }} py-1 transition-colors duration-200 border-b-2">
                    Laporan
                </a>

                {{-- Reviewer --}}
                <a href="{{ route('reviewer.dashboard') }}" 
                   class="{{ request()->routeIs('reviewer.*') ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent' }} py-1 transition-colors duration-200 border-b-2">
                    Reviewer
                </a>
                @endif

                {{-- Profil --}}
                <a href="{{ request()->routeIs('landing.index') ? '#profil' : route('landing.index') . '#profil' }}" 
                   @click="setActive('profil')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'profil' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Profil
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

                {{-- Peraturan --}}
                <a href="{{ request()->routeIs('landing.index') ? '#peraturan' : route('landing.index') . '#peraturan' }}" 
                   @click="setActive('peraturan')"
                   @if(request()->routeIs('landing.index'))
                   :class="activeSection === 'peraturan' ? 'text-[#00295f] font-bold border-[#00295f] dark:text-blue-400 dark:border-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent'"
                   @else
                   class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 border-transparent"
                   @endif
                   class="py-1 transition-colors duration-200 border-b-2">
                    Peraturan
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

                {{-- Tombol Login / Dashboard Admin --}}
                @if(session('admin_logged_in'))
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-gray-100 text-[#00295f] font-medium px-4 py-2 rounded-lg transition-colors duration-200 hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 text-xs">
                        Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="bg-gray-100 text-[#00295f] font-medium px-4 py-2 rounded-lg transition-colors duration-200 hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 text-xs">
                        Login Admin
                    </a>
                @endif

                {{-- Tombol Akses Portal --}}
                <a href="{{ route('portal.index') }}" 
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

        <a href="{{ route('katalog') }}" 
           @click="mobileMenuOpen = false" 
           class="block py-2.5 px-3 rounded-lg text-sm {{ request()->routeIs('katalog') || request()->routeIs('catalog.*') ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200' }} hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
            Katalog
        </a>

        @if($isLoggedIn)
        <a href="{{ route('hibah') }}" 
           @click="mobileMenuOpen = false" 
           class="block py-2.5 px-3 rounded-lg text-sm {{ request()->routeIs('hibah') ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200' }} hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
            Hibah
        </a>

        <a href="{{ route('surat.index') }}" 
           @click="mobileMenuOpen = false" 
           class="block py-2.5 px-3 rounded-lg text-sm {{ request()->routeIs('surat.*') ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200' }} hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
            Surat
        </a>

        <a href="{{ route('berkas.index') }}" 
           @click="mobileMenuOpen = false" 
           class="block py-2.5 px-3 rounded-lg text-sm {{ request()->routeIs('berkas.*') ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200' }} hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
            Laporan
        </a>

        <a href="{{ route('reviewer.dashboard') }}" 
           @click="mobileMenuOpen = false" 
           class="block py-2.5 px-3 rounded-lg text-sm {{ request()->routeIs('reviewer.*') ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200' }} hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
            Reviewer
        </a>
        @endif

        <a href="{{ request()->routeIs('landing.index') ? '#profil' : route('landing.index') . '#profil' }}" 
           @click="setActive('profil'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'profil' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Profil
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

        <a href="{{ request()->routeIs('landing.index') ? '#peraturan' : route('landing.index') . '#peraturan' }}" 
           @click="setActive('peraturan'); mobileMenuOpen = false" 
           @if(request()->routeIs('landing.index'))
           :class="activeSection === 'peraturan' ? 'text-[#00295f] font-bold bg-blue-50 dark:bg-gray-800 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
           @else
           class="text-gray-700 dark:text-gray-200 hover:text-[#00295f] dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800"
           @endif
           class="block py-2.5 px-3 rounded-lg text-sm transition-colors duration-200">
            Peraturan
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
            @if(session('admin_logged_in'))
                <a href="{{ route('admin.dashboard') }}" 
                   @click="mobileMenuOpen = false" 
                   class="w-full text-center py-2.5 px-4 rounded-lg text-xs bg-gray-100 text-[#00295f] font-medium transition-colors duration-200 hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">
                    Dashboard Admin
                </a>
            @else
                <a href="{{ route('login') }}" 
                   @click="mobileMenuOpen = false" 
                   class="w-full text-center py-2.5 px-4 rounded-lg text-xs bg-gray-100 text-[#00295f] font-medium transition-colors duration-200 hover:bg-gray-200 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">
                    Login Admin
                </a>
            @endif
            <a href="{{ route('portal.index') }}" 
               @click="mobileMenuOpen = false" 
               class="w-full text-center py-2.5 px-4 rounded-lg text-xs font-medium bg-[#00295f] text-white hover:bg-[#001c4a] dark:bg-blue-600">
                Akses Portal
            </a>
        </div>
    </div>
</nav>
