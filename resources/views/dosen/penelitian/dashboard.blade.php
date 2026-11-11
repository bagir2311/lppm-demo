<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen - LPPM Mahardika</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js untuk Dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Top Navigation Bar dengan Menu Profil -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Judul -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('mahardika-logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                    <span class="font-bold text-lg text-[#00295f]">Portal Dosen LPPM</span>
                </div>
                
                <!-- Dropdown Profil & Logout -->
                <div class="flex items-center" x-data="{ openProfile: false }">
                    <div class="relative">
                        <button @click="openProfile = !openProfile" @click.outside="openProfile = false" class="flex items-center gap-2 focus:outline-none hover:bg-gray-50 p-2 rounded-lg transition">
                            <div class="w-8 h-8 rounded-full bg-[#00295f] text-white flex items-center justify-center font-bold text-sm">
                                D
                            </div>
                            <span class="hidden md:block font-medium text-sm text-gray-700">Dosen Pengusul</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- Menu Dropdown -->
                        <div x-show="openProfile" x-transition style="display: none;" class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                <p class="text-sm font-bold text-gray-900">Halo, Dosen!</p>
                                <p class="text-xs text-gray-500 truncate">Hak Akses: Pengusul</p>
                            </div>
                            <a href="{{ route('landing.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#00295f] transition">Kembali ke Beranda</a>
                            
                            <!-- TOMBOL LOGOUT MUDAH -->
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-red-600 font-bold hover:bg-red-50 transition border-t border-gray-100 mt-1">
                                Keluar (Logout)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Dashboard -->
    <div class="pt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        <!-- Sapaan -->
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-gray-900">Selamat Datang di Dasbor Dosen</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola usulan penelitian, pengabdian, dan laporan Anda dengan mudah di sini.</p>
        </div>

        <!-- Statistik Ringkas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Laporan/Berkas</p>
                    <p class="text-3xl font-extrabold text-[#00295f] mt-1">{{ $totalBerkas }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-2xl">📂</div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengajuan Surat</p>
                    <p class="text-3xl font-extrabold text-[#00295f] mt-1">{{ $totalSurat }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-2xl">✉️</div>
            </div>
        </div>

        <!-- Menu Akses Cepat -->
        <h2 class="text-lg font-bold text-gray-900 mb-4">Akses Cepat Menu Dosen</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Menu Berkas/Laporan -->
            <a href="{{ route('berkas.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-200 hover:border-[#00295f] hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#00295f] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-900">Kelola Laporan / Berkas</h3>
                <p class="text-xs text-gray-500 mt-1">Unggah proposal dan cek status persetujuan admin.</p>
            </a>

            <!-- Menu Persuratan -->
            <a href="{{ route('surat.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-200 hover:border-[#00295f] hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#00295f] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-900">Layanan Persuratan</h3>
                <p class="text-xs text-gray-500 mt-1">Ajukan permohonan surat tugas atau izin penelitian.</p>
            </a>

            <!-- Menu Katalog/Hibah -->
            <a href="{{ route('katalog') }}" class="group bg-white p-5 rounded-2xl border border-gray-200 hover:border-[#00295f] hover:shadow-md transition-all">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#00295f] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="font-bold text-gray-900">Katalog & Hibah</h3>
                <p class="text-xs text-gray-500 mt-1">Lihat panduan dan e-katalog penelitian LPPM.</p>
            </a>

        </div>
    </div>
</body>
</html>