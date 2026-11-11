<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak {{ $surat->jenis_surat }} - {{ $surat->nomor_surat ?? 'LPPM-UM' }}</title>
    
    <style>
        /* ===================================================
           RESET & BASIC STYLING PERSIS TEMPLATE WORD KERTAS A4
           =================================================== */
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #e2e8f0;
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000000 !important; /* Hitam pekat, bukan gray */
        }

        /* Container Kertas A4 */
        .a4-page {
            width: 21cm;
            min-height: 29.7cm;
            margin: 25px auto;
            background: #ffffff !important;
            padding: 2cm 2cm 2cm 2.5cm; /* Standar Margin Surat Dinas: Atas 2cm, Kanan 2cm, Bawah 2cm, Kiri 2.5cm */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        /* Kop Surat Resmi Universitas */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000000;
            padding-bottom: 6px;
            margin-bottom: 18px;
        }

        .kop-table td {
            vertical-align: middle;
            color: #000000 !important;
        }

        .kop-logo {
            width: 80px;
            text-align: left;
        }

        .kop-logo img {
            width: 75px;
            height: auto;
            display: block;
        }

        .kop-text {
            text-align: center;
        }

        .kop-yayasan {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .kop-univ {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 2px 0;
            color: #00295f !important;
        }

        .kop-lppm {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }

        .kop-alamat {
            font-size: 8.5pt;
            font-style: italic;
            margin: 2px 0 0 0;
            color: #000000 !important;
        }

        /* Header Surat (Tanggal Kanan, Info Kiri) */
        .header-meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .header-meta-table td {
            vertical-align: top;
            font-size: 12pt;
            color: #000000 !important;
        }

        .info-col {
            width: 58%;
        }

        .date-col {
            width: 42%;
            text-align: right;
            padding-bottom: 12px;
        }

        .tujuan-block {
            margin-bottom: 18px;
            line-height: 1.4;
        }

        /* Isi Konten Surat */
        .content-body {
            text-align: justify;
            margin-bottom: 14px;
        }

        .content-body p {
            margin: 0 0 12px 0;
        }

        /* Tabel Data Peneliti (Uji Etik / Studi Pendahuluan) */
        .table-data-peneliti {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 14px 28px;
        }

        .table-data-peneliti td {
            padding: 3px 4px;
            vertical-align: top;
            font-size: 12pt;
            color: #000000 !important;
        }

        /* Tabel Bergaris Kasbangpol / Kolektif */
        .tabel-kolektif {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0;
        }

        .tabel-kolektif th,
        .tabel-kolektif td {
            border: 1px solid #000000 !important;
            padding: 6px 8px;
            font-size: 11pt;
            color: #000000 !important;
        }

        .tabel-kolektif th {
            background-color: #f3f4f6 !important;
            font-weight: bold;
            text-align: center;
        }

        /* Blok Tanda Tangan */
        .ttd-wrapper {
            width: 100%;
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
        }

        .ttd-box {
            width: 250px;
            text-align: left;
            font-size: 12pt;
            line-height: 1.35;
            color: #000000 !important;
        }

        .qr-seal {
            margin: 8px 0;
            display: inline-block;
        }

        /* Toolbar Layar (Tidak Tercetak) */
        .screen-toolbar {
            max-width: 21cm;
            margin: 16px auto 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            font-family: system-ui, -apple-system, sans-serif;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #00295f;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #001f48;
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        /* ===================================================
           ATURAN MEDIA PRINT (HANYA MENCETAK KERTAS A4)
           =================================================== */
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 25mm;
        }

        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .a4-page {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .tabel-kolektif th {
                background-color: #f3f4f6 !important;
            }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR LAYAR (DISEMBUNYIKAN SAAT PRINT) -->
    <div class="screen-toolbar no-print">
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ url()->previous() ?? route('surat.index') }}" class="btn btn-secondary">
                ← Kembali
            </a>
            <span style="font-size: 12px; color: #64748b;">
                Format Kertas A4 (Word Template Ready)
            </span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Cetak Dokumen / Simpan PDF
            </button>
        </div>
    </div>

    <!-- HALAMAN KERTAS A4 -->
    <div class="a4-page">
        
        <!-- KOP SURAT RESMI MAHARDIKA -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    <img src="{{ asset('mahardika-logo.png') }}" alt="Logo Mahardika">
                </td>
                <td class="kop-text">
                    <div class="kop-yayasan">YAYASAN PENDIDIKAN DAN KESEHATAN MAHARDIKA</div>
                    <div class="kop-univ">UNIVERSITAS INSAN MAHARDIKA</div>
                    <div class="kop-lppm">LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT (LPPM)</div>
                    <div class="kop-alamat">
                        Jl. Kesambi No. 202, Kesambi, Kota Cirebon, Jawa Barat 45134<br>
                        Telp. (0231) 206123 | Website: https://lppm.mahardika.ac.id | Email: lppm@mahardika.ac.id
                    </div>
                </td>
            </tr>
        </table>

        @php
            $jenisLower = strtolower($surat->jenis_surat ?? '');
            $isUjiEtik = str_contains($jenisLower, 'etik') || str_contains($jenisLower, 'ethical');
            $isStudiPendahuluan = str_contains($jenisLower, 'pendahuluan');
            $isKasbangpol = !$isUjiEtik && !$isStudiPendahuluan;
            
            // Perihal Dinamis
            $perihal = $surat->perihal ?? (
                $isUjiEtik ? 'Permohonan Komite Etik Riset' : (
                    $isStudiPendahuluan ? 'Permohonan Izin Studi Pendahuluan' : 'Permohonan Izin Penelitian'
                )
            );

            // Format tanggal surat
            $tglSurat = $surat->approved_at 
                ? \Carbon\Carbon::parse($surat->approved_at)->translatedFormat('d F Y') 
                : ($surat->created_at ? $surat->created_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y'));
        @endphp

        <!-- 1. HEADER (TANGGAL KANAN ATAS & NOMOR/LAMPIRAN/PERIHAL KIRI ATAS) -->
        <table class="header-meta-table">
            <tr>
                <!-- Nomor, Lampiran, Perihal (Kiri Atas) -->
                <td class="info-col">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 75px;">Nomor</td>
                            <td style="width: 15px; text-align: center;">:</td>
                            <td><strong>{{ $surat->nomor_surat ?? '...../LPPM-UM/..../2026' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Lampiran</td>
                            <td style="text-align: center;">:</td>
                            <td>{{ $surat->lampiran ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Perihal</td>
                            <td style="text-align: center;">:</td>
                            <td><strong>{{ $perihal }}</strong></td>
                        </tr>
                    </table>
                </td>

                <!-- Tanggal (Kanan Atas) -->
                <td class="date-col">
                    Cirebon, {{ $tglSurat }}
                </td>
            </tr>
        </table>

        <!-- Kepada Yth (Kiri Tengah) -->
        <div class="tujuan-block">
            <div>Kepada Yth:</div>
            <div><strong>{{ $surat->instansi_tujuan }}</strong></div>
            <div>di</div>
            <div>Tempat</div>
        </div>

        <!-- 2. ISI SURAT (KONDISI IF-ELSE BERDASARKAN JENIS SURAT) -->
        <div class="content-body">
            
            <!-- A. JIKA UJI ETIK -->
            @if($isUjiEtik)
                <p>
                    Assalamualaikum Wr.Wb.<br>
                    Dalam rangka proses penyusunan Penelitian Dosen Universitas Insan Mahardika Kota Cirebon, bersama ini kami mengajukan permohonan Peneliti tersebut dibawah ini:
                </p>

                <table class="table-data-peneliti">
                    <tr>
                        <td style="width: 110px; font-weight: bold;">Nama</td>
                        <td style="width: 15px; text-align: center;">:</td>
                        <td>{{ $surat->nama_dosen }}</td>
                    </tr>
                    @if($surat->nidn)
                    <tr>
                        <td style="font-weight: bold;">NIDN / NIP</td>
                        <td style="text-align: center;">:</td>
                        <td>{{ $surat->nidn }}</td>
                    </tr>
                    @endif
                    @if($surat->prodi)
                    <tr>
                        <td style="font-weight: bold;">Program Studi</td>
                        <td style="text-align: center;">:</td>
                        <td>{{ $surat->prodi }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-weight: bold;">Judul</td>
                        <td style="text-align: center;">:</td>
                        <td><strong>{{ $surat->judul_riset ?? $surat->keperluan }}</strong></td>
                    </tr>
                </table>

            <!-- B. JIKA STUDI PENDAHULUAN -->
            @elseif($isStudiPendahuluan)
                <p>
                    Assalamualaikum Wr.Wb.<br>
                    Dalam rangka proses penyusunan Penelitian Dosen Universitas Insan Mahardika Kota Cirebon, bersama ini kami mengajukan permohonan Peneliti tersebut dibawah ini:
                </p>

                <table class="table-data-peneliti">
                    <tr>
                        <td style="width: 110px; font-weight: bold;">Nama</td>
                        <td style="width: 15px; text-align: center;">:</td>
                        <td>{{ $surat->nama_dosen }}</td>
                    </tr>
                    @if($surat->nidn)
                    <tr>
                        <td style="font-weight: bold;">NIDN / NIP</td>
                        <td style="text-align: center;">:</td>
                        <td>{{ $surat->nidn }}</td>
                    </tr>
                    @endif
                    @if($surat->prodi)
                    <tr>
                        <td style="font-weight: bold;">Program Studi</td>
                        <td style="text-align: center;">:</td>
                        <td>{{ $surat->prodi }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-weight: bold;">Judul</td>
                        <td style="text-align: center;">:</td>
                        <td><strong>{{ $surat->judul_riset ?? $surat->keperluan }}</strong></td>
                    </tr>
                </table>

                <p style="margin-top: 10px;">
                    diberi ijin melaksanakan Penelitian di {{ $surat->instansi_tujuan }}.
                </p>

            <!-- C. JIKA IZIN PENELITIAN / KASBANGPOL (KOLEKTIF) -->
            @else
                <p>
                    Assalamualaikum Wr.Wb.<br>
                    Dalam rangka proses penyusunan Penelitian Dosen Universitas Insan Mahardika Kota Cirebon, bersama ini kami mengajukan permohonan Peneliti tersebut dibawah ini:
                </p>

                <table class="tabel-kolektif">
                    <thead>
                        <tr>
                            <th style="width: 8%;">No</th>
                            <th style="width: 37%; text-align: left;">Nama</th>
                            <th style="width: 55%; text-align: left;">Judul Penelitian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surat->anggota as $index => $anggota)
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ is_array($anggota) ? ($anggota['nama'] ?? '') : ($anggota->nama ?? $anggota) }}</strong>
                            </td>
                            <td>
                                {{ is_array($anggota) ? ($anggota['judul'] ?? $surat->judul_riset ?? $surat->keperluan) : ($anggota->judul ?? $surat->judul_riset ?? $surat->keperluan) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="margin-top: 14px;">
                    diberi ijin melaksanakan Penelitian di {{ $surat->instansi_tujuan }}.
                </p>
            @endif

            <!-- 3. PENUTUP -->
            <p style="margin-top: 16px;">
                Demikian agar menjadi maklum. Atas perhatian serta kerjasamanya, kami ucapkan terima kasih.<br>
                Wa'alaikum Salam Warohmatullah Wabarokatuh.
            </p>

        </div>

        <!-- TANDA TANGAN (KANAN BAWAH) -->
        <div class="ttd-wrapper">
            <div class="ttd-box">
                @if($isStudiPendahuluan)
                    <!-- TTD STUDI PENDAHULUAN: an. Rektor / Wakil Rektor I -->
                    <div>an. Rektor</div>
                    <div style="margin-bottom: 4px;">Wakil Rektor I,</div>

                    @if(!empty($surat->qr_code_hash))
                    <div class="qr-seal">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->errorCorrection('H')->generate(route('surat.verify', ['hash' => $surat->qr_code_hash])) !!}
                    </div>
                    @else
                    <div style="height: 60px;"></div>
                    @endif

                    <div><strong><u>Nonok Karlina, M.Kep., Ns., Sp.Kep.MB</u></strong></div>
                    <div>NIK. 2.1.07.02.023</div>
                @else
                    <!-- TTD SELAIN ITU: Rektor -->
                    <div style="margin-bottom: 4px;">Rektor,</div>

                    @if(!empty($surat->qr_code_hash))
                    <div class="qr-seal">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->errorCorrection('H')->generate(route('surat.verify', ['hash' => $surat->qr_code_hash])) !!}
                    </div>
                    @else
                    <div style="height: 65px;"></div>
                    @endif

                    <div><strong><u>Dr. Yani Kamasturyani, SKM., M.H.Kes.</u></strong></div>
                    <div>NIK. 2.0.14.00.163</div>
                @endif
            </div>
        </div>

    </div>

</body>
</html>
