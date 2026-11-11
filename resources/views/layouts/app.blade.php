<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LPPM Universitas Insan Mahardika - Lembaga Penelitian dan Pengabdian kepada Masyarakat')</title>
    
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <!-- Meta SEO -->
    <meta name="description" content="@yield('meta_description', 'Portal resmi Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) Universitas Insan Mahardika. Informasi hibah internal/nasional, pengabdian, publikasi jurnal, direktori dosen, dan regulasi akademik.')">
    <meta name="keywords" content="LPPM, Mahardika, Universitas Insan Mahardika, Penelitian, Pengabdian Masyarakat, Jurnal Mahardika, JKM, Dosen, Proposal Hibah, SINTA, Kemdikbudristek">
    <meta name="author" content="LPPM Universitas Insan Mahardika">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="LPPM Universitas Insan Mahardika">
    <meta property="og:description" content="Pusat informasi penelitian, pengabdian masyarakat, publikasi ilmiah, dan pelayanan hibah akademik Universitas Insan Mahardika.">
    <meta property="og:url" content="https://lppm.mahardika.ac.id">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('mahardika-logo.png') }}">
</head>
<body class="bg-bg-main text-text-main antialiased selection:bg-mahardika-navy selection:text-white dark:selection:bg-navy-accent transition-colors duration-300">
    
    <!-- Header / Navbar -->
    @include('components.navbar')

    <!-- Content Area -->
    <main class="w-full min-h-screen pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Announcement Popup -->
    @include('components.announcement-popup')

</body>
</html>
