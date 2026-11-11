<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->jenis_surat }} - {{ $surat->nomor_surat ?? 'LPPM-UM' }}</title>
    <style>
        @page {
            margin: 30px 45px 30px 45px;
            size: a4 portrait;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-col {
            width: 80px;
            text-align: left;
        }
        .logo-col img {
            width: 75px;
            height: auto;
        }
        .title-col {
            text-align: center;
        }
        .univ-sub {
            font-size: 10.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .univ-title {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 2px 0;
            color: #00295f;
            text-transform: uppercase;
        }
        .lppm-title {
            font-size: 11.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 2px 0;
            text-transform: uppercase;
        }
        .alamat-text {
            font-size: 8pt;
            font-style: italic;
            margin: 2px 0 0 0;
            color: #333;
        }
        .surat-heading {
            text-align: center;
            margin-bottom: 18px;
        }
        .surat-heading h3 {
            font-size: 12.5pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        .surat-heading p {
            font-size: 10.5pt;
            margin: 0;
            font-weight: bold;
        }
        .content-body {
            text-align: justify;
            margin-bottom: 12px;
        }
        .content-body p {
            margin: 0 0 10px 0;
            text-indent: 28px;
        }
        .content-body p.no-indent {
            text-indent: 0;
        }
        .data-table {
            width: 95%;
            margin: 10px auto 14px auto;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 3px 4px;
            vertical-align: top;
            font-size: 11pt;
        }
        .data-label {
            width: 32%;
            font-weight: bold;
        }
        .data-colon {
            width: 3%;
            text-align: center;
        }
        .data-value {
            width: 65%;
        }
        .signature-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        .signature-table td {
            vertical-align: top;
        }
        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-city {
            margin-bottom: 4px;
            font-size: 10.5pt;
        }
        .signature-role {
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 8px;
            line-height: 1.25;
        }
        .qr-wrapper {
            margin: 4px auto 6px auto;
            display: inline-block;
            padding: 4px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #fff;
        }
        .qr-wrapper img {
            display: block;
        }
        .signature-notice {
            font-size: 7pt;
            color: #00295f;
            font-weight: bold;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 11pt;
            text-decoration: underline;
            margin-top: 2px;
        }
        .signature-nidn {
            font-size: 9.5pt;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Resmi -->
    <table class="header-table">
        <tr>
            <td class="logo-col">
                @if(!empty($logoBase64))
                    <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo Mahardika">
                @else
                    <img src="{{ public_path('mahardika-logo.png') }}" alt="Logo Mahardika">
                @endif
            </td>
            <td class="title-col">
                <p class="univ-sub">Yayasan Informatika Mahardika</p>
                <h1 class="univ-title">UNIVERSITAS INSAN MAHARDIKA</h1>
                <p class="lppm-title">Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)</p>
                <p class="alamat-text">Jl. Terusan Pemuda No. 8, Kesambi, Kota Cirebon, Jawa Barat 45132 | Email: lppm@mahardika.ac.id | Web: lppm.mahardika.ac.id</p>
            </td>
        </tr>
    </table>

    <!-- Judul & Nomor Surat Resmi -->
    <div class="surat-heading">
        <h3>{{ $surat->jenis_surat }}</h3>
        <p>Nomor: {{ $surat->nomor_surat ?? 'LPPM/UM/' . date('Y') . '/' . $surat->id }}</p>
    </div>

    <!-- Isi Surat Resmi -->
    <div class="content-body">
        <p class="no-indent">
            Kepala Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) Universitas Insan Mahardika menerangkan dengan sebenarnya bahwa:
        </p>

        <!-- Tabel Auto-fill Data Dosen -->
        <table class="data-table">
            <tr>
                <td class="data-label">Nama Dosen / Peneliti</td>
                <td class="data-colon">:</td>
                <td class="data-value"><strong>{{ $surat->nama_dosen }}</strong></td>
            </tr>
            <tr>
                <td class="data-label">NIDN / NIP</td>
                <td class="data-colon">:</td>
                <td class="data-value">{{ $surat->nidn }}</td>
            </tr>
            <tr>
                <td class="data-label">Program Studi / Fakultas</td>
                <td class="data-colon">:</td>
                <td class="data-value">{{ $surat->prodi }}</td>
            </tr>
            <tr>
                <td class="data-label">Keperluan / Judul Kegiatan</td>
                <td class="data-colon">:</td>
                <td class="data-value"><strong>"{{ $surat->keperluan }}"</strong></td>
            </tr>
            <tr>
                <td class="data-label">Instansi / Lokasi Tujuan</td>
                <td class="data-colon">:</td>
                <td class="data-value">{{ $surat->instansi_tujuan }}</td>
            </tr>
        </table>

        <p>
            Adalah benar merupakan staf pengajar / dosen tetap di lingkungan Universitas Insan Mahardika yang diberikan izin resmi untuk melaksanakan kegiatan akademik sebagaimana tercantum di atas pada instansi yang dituju.
        </p>
        <p>
            Sehubungan dengan hal tersebut di atas, kami memohon perkenan Bapak/Ibu pimpinan instansi terkait dapat memberikan izin, fasilitas, serta kerja sama yang diperlukan demi kelancaran pelaksanaan kegiatan riset dan pengabdian tersebut.
        </p>
        <p>
            Demikian surat permohonan/keterangan ini kami terbitkan dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya. Atas perhatian, bantuan, dan kerja sama yang baik dari Bapak/Ibu, kami sampaikan terima kasih.
        </p>
    </div>

    <!-- Tanda Tangan & QR Code e-Signature di Pojok Kanan Bawah -->
    <table class="signature-table">
        <tr>
            <td width="55%"></td>
            <td width="45%">
                <div class="signature-box">
                    <div class="signature-city">
                        Cirebon, {{ $surat->approved_at ? $surat->approved_at->format('d F Y') : date('d F Y') }}
                    </div>
                    <div class="signature-role">
                        Kepala LPPM Univ. Insan Mahardika,
                    </div>
                    
                    <!-- QR Code Digital Signature -->
                    <div class="qr-wrapper">
                        <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" width="92" height="92" alt="QR Code Verifikasi TTD">
                    </div>

                    <div class="signature-notice">
                        ✔ TERTANDA TANGAN ELEKTRONIK (E-SIGNATURE)<br>
                        LPPM UNIVERSITAS INSAN MAHARDIKA
                    </div>

                    <div class="signature-name">
                        Dr. Ns. Hendra Setiawan, M.Kep.
                    </div>
                    <div class="signature-nidn">
                        NIDN. 0418097801
                    </div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
