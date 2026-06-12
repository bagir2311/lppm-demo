<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - LPPM Universitas Insan Mahardika</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js & Tailwind CSS Assets (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-bg-alt text-text-main dark:bg-[#0b1220] dark:text-dark-text-muted transition-colors duration-300 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Background Abstract shapes -->
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none dark:opacity-10">
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-mahardika-navy-pale blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-success-green-pale blur-3xl"></div>
    </div>

    <!-- Center Card panel -->
    <div class="w-full max-w-md bg-white border border-border-main p-8 rounded-3xl shadow-floating dark:bg-dark-surface dark:border-dark-border z-10 relative">
        
        <!-- Header Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-mahardika-navy text-white dark:bg-navy-accent shadow-card mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
            </div>
            
            <h1 class="font-primary font-extrabold text-2xl text-text-main">Administrator Login</h1>
            <span class="text-xs text-text-secondary dark:text-dark-text-muted mt-1 inline-block">Portal Pengelolaan Landing Page & CMS LPPM</span>
        </div>

        <!-- Session Feedback Alerts -->
        @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-alert-red-pale border border-alert-red/20 text-alert-red font-semibold text-xs flex items-center space-x-2">
            <svg class="w-5 h-5 text-alert-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="email" class="block text-[10px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted mb-2">Email Administrator</label>
                <div class="relative">
                    <input type="email" 
                           name="email"
                           id="email"
                           required
                           placeholder="superadmin@mahardika.ac.id"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-border-main bg-bg-surface text-xs focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:bg-dark-surface-soft dark:border-dark-border dark:focus:ring-navy-accent/25">
                    <svg class="w-5 h-5 text-text-disabled absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                </div>
            </div>

            <div>
                <label for="password" class="block text-[10px] font-bold uppercase tracking-wider text-text-secondary dark:text-dark-text-muted mb-2">Kata Sandi</label>
                <div class="relative">
                    <input type="password" 
                           name="password"
                           id="password"
                           required
                           placeholder="••••••••"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-border-main bg-bg-surface text-xs focus:outline-none focus:ring-2 focus:ring-mahardika-navy/20 dark:bg-dark-surface-soft dark:border-dark-border dark:focus:ring-navy-accent/25">
                    <svg class="w-5 h-5 text-text-disabled absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
            </div>

            <!-- Pre-fill Details reminder for demo correctness -->
            <div class="p-3.5 rounded-xl bg-mahardika-navy-pale border border-mahardika-navy/10 text-mahardika-navy dark:bg-dark-surface-soft/60 dark:border-dark-border dark:text-navy-accent text-[10px] space-y-1">
                <span class="block font-bold">Kredensial Pengujian CMS (Simulasi):</span>
                <span class="block font-semibold">Email: <span class="underline">superadmin@mahardika.ac.id</span></span>
                <span class="block font-semibold">Password: <span class="underline">password</span></span>
            </div>

            <!-- Submit trigger button -->
            <button type="submit" class="w-full py-3 rounded-xl font-primary font-bold text-center text-sm bg-mahardika-navy text-white hover:bg-mahardika-navy-soft shadow-subtle hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 dark:bg-navy-accent dark:hover:bg-mahardika-navy-soft">
                Masuk Sistem Admin
            </button>

            <!-- Home button -->
            <a href="{{ route('landing.index') }}" class="block text-center text-xs text-text-disabled hover:text-mahardika-navy dark:hover:text-navy-accent transition-colors duration-150">
                Kembali ke Beranda Landing Page
            </a>
        </form>

    </div>

</body>
</html>
