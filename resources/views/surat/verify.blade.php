<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Surat - LPPM Universitas Insan Mahardika</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen py-10 px-4 sm:px-6 lg:px-8">

    <div class="max-w-2xl mx-auto space-y-6">
        
        <!-- Header Logo -->
        <div class="text-center space-y-2">
            <img src="{{ asset('mahardika-logo.png') }}" alt="Logo Universitas Insan Mahardika" class="w-16 h-16 mx-auto object-contain">
            <h1 class="text-lg font-bold text-[#00295f] uppercase tracking-wide">Universitas Insan Mahardika</h1>
            <p class="text-xs text-gray-500 font-medium">Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)</p>
            <div class="inline-block bg-blue-100 text-[#00295f] px-3 py-1 rounded-full text-xs font-semibold">
                Sistem Verifikasi Dokumen Elektronik Resmi
            </div>
        </div>

        @if($surat)
            <!-- Status Card Valid -->
            <div class="bg-white rounded-2xl border border-green-200 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-4 border-b border-gray-100 pb-5">
                    <div class="w-12 h-12 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-green-800">DOKUMEN RESMI TERCATAT</h2>
                        <p class="text-xs text-gray-500">Surat ini sah dan telah ditandatangani secara elektronik oleh Kepala LPPM.</p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 font-medium block">Nomor Surat</span>
                        <span class="text-sm font-bold text-gray-900 font-mono">{{ $surat->nomor_surat ?? '-' }}</span>
                    </div>

                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 font-medium block">Jenis Surat</span>
                        <span class="text-sm font-bold text-gray-900">{{ $surat->jenis_surat }}</span>
                    </div>

                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 font-medium block">Nama Dosen / Peneliti</span>
                        <span class="text-sm font-bold text-gray-900">{{ $surat->nama_dosen }}</span>
                        <span class="text-[11px] text-gray-500 block">NIDN: {{ $surat->nidn }}</span>
                    </div>

                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 font-medium block">Program Studi</span>
                        <span class="text-sm font-bold text-gray-900">{{ $surat->prodi }}</span>
                    </div>

                    <div class="sm:col-span-2 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 font-medium block">Keperluan / Judul Riset</span>
                        <span class="text-xs font-semibold text-gray-800 mt-0.5 block leading-relaxed">{{ $surat->keperluan }}</span>
                    </div>

                    <div class="sm:col-span-2 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 font-medium block">Instansi / Lokasi Tujuan</span>
                        <span class="text-xs font-semibold text-gray-800 mt-0.5 block">{{ $surat->instansi_tujuan }}</span>
                    </div>

                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 font-medium block">Tanggal Disetujui</span>
                        <span class="text-xs font-bold text-gray-800">{{ $surat->approved_at ? $surat->approved_at->format('d M Y, H:i') . ' WIB' : '-' }}</span>
                    </div>

                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 font-medium block">Penandatangan Digital</span>
                        <span class="text-xs font-bold text-gray-800">Dr. Ns. Hendra Setiawan, M.Kep.</span>
                        <span class="text-[10px] text-gray-500 block">Kepala LPPM Mahardika</span>
                    </div>
                </div>

                <!-- Footer Certificate -->
                <div class="pt-4 border-t border-gray-100 text-center space-y-3">
                    <p class="text-[11px] text-gray-400 font-mono break-all">
                        Digital Hash: {{ $surat->qr_code_hash }}
                    </p>
                    <a href="{{ route('surat.pdf', $surat->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#00295f] hover:bg-blue-900 text-white text-xs font-bold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Unduh / Cetak Dokumen PDF Asli</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Status Card Invalid -->
            <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-6 sm:p-8 space-y-4 text-center">
                <div class="w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h2 class="text-lg font-extrabold text-red-700">DOKUMEN TIDAK VALID / TIDAK DITEMUKAN</h2>
                <p class="text-xs text-gray-600 max-w-md mx-auto">
                    Kode verifikasi QR Code tidak terdaftar dalam pangkalan data LPPM Universitas Insan Mahardika. Pastikan Anda memindai kode QR dari dokumen resmi terbaru.
                </p>
                <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-200 text-xs font-mono text-gray-500 break-all">
                    Hash: {{ $hash ?? 'Tidak Diketahui' }}
                </div>
            </div>
        @endif

        <div class="text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} LPPM Universitas Insan Mahardika. Seluruh Hak Cipta Dilindungi.
        </div>

    </div>

</body>
</html>
