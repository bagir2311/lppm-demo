<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->jenis_surat }} - {{ $surat->nomor_surat }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.6; margin: 40px; color: #000; }
        .kop-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 25px; position: relative; }
        .logo { position: absolute; left: 10px; top: 5px; width: 75px; }
        .univ-title { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .lppm-title { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin: 0; color: #00295f; }
        .alamat { font-size: 9pt; font-style: italic; margin-top: 5px; }
        .surat-title { text-align: center; margin-bottom: 20px; }
        .surat-title h3 { text-decoration: underline; margin: 0; text-transform: uppercase; font-size: 13pt; }
        .surat-title p { margin: 0; font-size: 11pt; font-weight: bold; }
        .content { margin-bottom: 30px; text-align: justify; }
        .table-data { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .table-data td { padding: 4px 8px; vertical-align: top; }
        .ttd-section { float: right; width: 250px; text-align: center; margin-top: 30px; }
        .stempel-digital { border: 2px dashed #00295f; border-radius: 8px; padding: 10px; margin-top: 10px; color: #00295f; font-size: 9pt; font-weight: bold; text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <!-- Print Button Bar -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right; background: #f1f5f9; padding: 10px; border-radius: 8px;">
        <button onclick="window.print()" style="background: #00295f; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="kop-header">
        <img src="{{ asset('mahardika-logo.png') }}" class="logo" alt="Logo">
        <p class="univ-title">UNIVERSITAS INSAN MAHARDIKA</p>
        <p class="lppm-title">LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT (LPPM)</p>
        <p class="alamat">Jl. Terusan Pemuda No. 8, Cirebon, Jawa Barat | Email: lppm@mahardika.ac.id | Web: lppm.mahardika.ac.id</p>
    </div>

    <!-- Judul & Nomor Surat -->
    <div class="surat-title">
        <h3>{{ $surat->jenis_surat }}</h3>
        <p>Nomor: {{ $surat->nomor_surat ?? 'B/102/LPPM-UM/III/2026' }}</p>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini Kepala Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) Universitas Insan Mahardika menerangkan bahwa:</p>

        <table class="table-data">
            <tr>
                <td width="30%"><strong>Nama Dosen / Peneliti</strong></td>
                <td width="5%">:</td>
                <td>{{ $surat->dosen->nama ?? 'Dr. Apt. Rina Kusuma, M.Farm.' }}</td>
            </tr>
            <tr>
                <td><strong>NIDN / NIP</strong></td>
                <td>:</td>
                <td>{{ $surat->dosen->nidn ?? '0412088501' }}</td>
            </tr>
            <tr>
                <td><strong>Program Studi / Fakultas</strong></td>
                <td>:</td>
                <td>{{ $surat->dosen->prodi ?? 'Farmasi' }} / {{ $surat->dosen->fakultas ?? 'Ilmu Kesehatan' }}</td>
            </tr>
        </table>

        <p>Adalah benar merupakan peneliti utama yang akan melaksanakan kegiatan akademik dengan rincian berikut:</p>

        <table class="table-data">
            <tr>
                <td width="30%"><strong>Judul Riset / Kegiatan</strong></td>
                <td width="5%">:</td>
                <td><strong>"{{ $surat->judul_penelitian }}"</strong></td>
            </tr>
            <tr>
                <td><strong>Lokasi Target / Mitra</strong></td>
                <td>:</td>
                <td>{{ $surat->lokasi_penelitian ?? 'RSUD / Puskesmas Pedesaan' }}</td>
            </tr>
            <tr>
                <td><strong>Waktu Pelaksanaan</strong></td>
                <td>:</td>
                <td>{{ date('d F Y', strtotime($surat->tanggal_mulai ?? now())) }} s/d {{ date('d F Y', strtotime($surat->tanggal_selesai ?? now()->addMonth())) }}</td>
            </tr>
        </table>

        <p>Demikian surat ini diterbitkan untuk dipergunakan sebagaimana mestinya dan agar pihak terkait dapat memberikan bantuan serta fasilitas yang diperlukan.</p>
    </div>

    <!-- Tanda Tangan & Stempel Digital -->
    <div class="ttd-section">
        <p>Cirebon, {{ date('d F Y') }}<br>Kepala LPPM Univ. Insan Mahardika,</p>
        
        <div class="stempel-digital">
            ✔ VERIFIED DIGITAL STAMP<br>
            LPPM UNIVERSITAS INSAN MAHARDIKA<br>
            <span style="font-size: 7pt; font-weight: normal; color: #444;">Token: {{ md5($surat->id . $surat->nomor_surat) }}</span>
        </div>

        <p style="margin-top: 15px;"><strong><u>Dr. Ns. Hendra Setiawan, M.Kep.</u></strong><br>NIDN. 0418097801</p>
    </div>

</body>
</html>
