<nav x-data="{ mobileMenuOpen: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
     :class="{ 'bg-bg-main/80 backdrop-blur-md border-b border-border-main/50 shadow-subtle': scrolled, 'bg-bg-main/30 border-b border-transparent': !scrolled }"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ request()->routeIs('landing.index') ? '#hero' : route('landing.index') . '#hero' }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('mahardika-logo.png') }}" alt="Logo Mahardika" class="w-full h-full object-contain drop-shadow-sm">
                </div>
                <div class="flex flex-col">
                    <span class="font-primary font-extrabold text-lg tracking-tight text-mahardika-navy dark:text-white leading-none">LPPM MAHARDIKA</span>
                    <span class="font-body text-xs text-text-secondary dark:text-dark-text-muted tracking-wider uppercase font-semibold">Univ. Insan Mahardika</span>
                </div>
            </a>

            <!-- Desktop Menu (Hidden on Mobile) -->
            <div class="hidden lg:flex items-center space-x-5">
                <a href="{{ request()->routeIs('landing.index') ? '#hero' : route('landing.index') . '#hero' }}" class="font-primary text-sm font-semibold text-text-main hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-200">Beranda</a>
                <a href="{{ request()->routeIs('landing.index') ? '#profil' : route('landing.index') . '#profil' }}" class="font-primary text-sm font-semibold text-text-main hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-200">Profil</a>
                <a href="{{ request()->routeIs('landing.index') ? '#pengumuman' : route('landing.index') . '#pengumuman' }}" class="font-primary text-sm font-semibold text-text-main hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-200">Pengumuman</a>
                <a href="{{ request()->routeIs('landing.index') ? '#dosen' : route('landing.index') . '#dosen' }}" class="font-primary text-sm font-semibold text-text-main hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-200">Dosen</a>
                <a href="{{ request()->routeIs('landing.index') ? '#peraturan' : route('landing.index') . '#peraturan' }}" class="font-primary text-sm font-semibold text-text-main hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-200">Peraturan</a>
                <a href="{{ request()->routeIs('landing.index') ? '#jurnal' : route('landing.index') . '#jurnal' }}" class="font-primary text-sm font-semibold text-text-main hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-200">Jurnal</a>
                <a href="{{ request()->routeIs('landing.index') ? '#kontak' : route('landing.index') . '#kontak' }}" class="font-primary text-sm font-semibold text-text-main hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-200">Kontak</a>
            </div>

            <!-- Header Action Items (Dark Mode Toggle, Portal CTAs, Admin) -->
            <div class="hidden lg:flex items-center space-x-3">
                <!-- Dark Mode Toggle Button -->
                <button @click="darkMode = !darkMode" 
                        class="p-2 rounded-xl border border-border-main/55 bg-bg-main/55 text-text-sub hover:text-mahardika-navy dark:hover:text-navy-accent hover:border-mahardika-navy/30 dark:bg-dark-surface/50 dark:border-dark-border transition-all duration-300"
                        aria-label="Toggle Dark Mode">
                    <!-- Sun Icon (shown when in Dark Mode) -->
                    <svg x-show="darkMode" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                    <!-- Moon Icon (shown when in Light Mode) -->
                    <svg x-show="!darkMode" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Login Admin Link -->
                <a href="{{ route('admin.login') }}" 
                   class="px-3.5 py-2 rounded-xl text-xs font-semibold border border-border-main hover:bg-mahardika-navy-pale text-text-sub hover:text-mahardika-navy dark:border-dark-border dark:hover:bg-dark-surface-soft dark:text-white transition-all duration-150">
                    Login Admin
                </a>

                <!-- Akses Portal Link (No dropdown, direct to /portal) -->
                <a href="{{ route('portal.index') }}" 
                   class="px-4.5 py-2 rounded-xl text-xs font-semibold bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft">
                    Akses Portal
                </a>
            </div>

            <!-- Hamburger Button for Mobile -->
            <div class="flex items-center space-x-3 lg:hidden">
                <!-- Mobile Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" 
                        class="p-2 rounded-lg border border-border-main/50 bg-bg-main/50 text-text-sub dark:border-dark-border"
                        aria-label="Toggle Dark Mode Mobile">
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Hamburger Trigger -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="p-2 rounded-lg border border-border-main/50 text-text-main hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-colors duration-200"
                        aria-label="Open Navigation Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer (Slide Down Transition) -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform -translate-y-4 opacity-0"
         x-transition:enter-end="transform translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="transform translate-y-0 opacity-100"
         x-transition:leave-end="transform -translate-y-4 opacity-0"
         class="lg:hidden bg-bg-surface border-b border-border-main shadow-floating px-4 pt-2 pb-6 space-y-2 dark:bg-dark-surface dark:border-dark-border"
         style="display: none;">
        
        <a href="{{ request()->routeIs('landing.index') ? '#hero' : route('landing.index') . '#hero' }}" @click="mobileMenuOpen = false" class="block py-3 px-4 rounded-xl text-sm font-semibold text-text-main hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft transition-colors duration-200">Beranda</a>
        <a href="{{ request()->routeIs('landing.index') ? '#profil' : route('landing.index') . '#profil' }}" @click="mobileMenuOpen = false" class="block py-3 px-4 rounded-xl text-sm font-semibold text-text-main hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft transition-colors duration-200">Profil LPPM</a>
        <a href="{{ request()->routeIs('landing.index') ? '#pengumuman' : route('landing.index') . '#pengumuman' }}" @click="mobileMenuOpen = false" class="block py-3 px-4 rounded-xl text-sm font-semibold text-text-main hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft transition-colors duration-200">Pengumuman</a>
        <a href="{{ request()->routeIs('landing.index') ? '#dosen' : route('landing.index') . '#dosen' }}" @click="mobileMenuOpen = false" class="block py-3 px-4 rounded-xl text-sm font-semibold text-text-main hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft transition-colors duration-200">Direktori Dosen</a>
        <a href="{{ request()->routeIs('landing.index') ? '#peraturan' : route('landing.index') . '#peraturan' }}" @click="mobileMenuOpen = false" class="block py-3 px-4 rounded-xl text-sm font-semibold text-text-main hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft transition-colors duration-200">Peraturan & SOP</a>
        <a href="{{ request()->routeIs('landing.index') ? '#jurnal' : route('landing.index') . '#jurnal' }}" @click="mobileMenuOpen = false" class="block py-3 px-4 rounded-xl text-sm font-semibold text-text-main hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft transition-colors duration-200">Jurnal JKM</a>
        <a href="{{ request()->routeIs('landing.index') ? '#kontak' : route('landing.index') . '#kontak' }}" @click="mobileMenuOpen = false" class="block py-3 px-4 rounded-xl text-sm font-semibold text-text-main hover:bg-mahardika-navy-pale dark:hover:bg-dark-surface-soft transition-colors duration-200">Kontak</a>

        <div class="h-px bg-border-main my-3 dark:bg-dark-border"></div>

        <!-- Quick Access CTA Mobile -->
        <a href="{{ route('portal.index') }}" @click="mobileMenuOpen = false" class="w-full flex items-center justify-center py-3 px-4 rounded-xl text-sm font-bold bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft transition-all duration-200">
            <span>Akses Portal</span>
        </a>
        <a href="{{ route('admin.login') }}" @click="mobileMenuOpen = false" class="w-full flex items-center justify-center py-3 px-4 mt-2 rounded-xl text-sm font-semibold border border-border-main text-text-main hover:bg-mahardika-navy-pale dark:border-dark-border dark:hover:bg-dark-surface-soft transition-all duration-200">
            <span>Sistem Login Admin</span>
        </a>
    </div>
</nav>
