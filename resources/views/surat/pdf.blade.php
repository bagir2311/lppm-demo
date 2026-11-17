<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->jenis_surat }} - {{ $surat->nomor_surat ?? 'UIM' }}</title>
    <style>
        @page {
            margin: 25px 45px 30px 50px;
            size: a4 portrait;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2.5px double #000;
            padding-bottom: 6px;
            margin-bottom: 14px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 75px;
            text-align: left;
        }
        .kop-logo img {
            width: 70px;
            height: auto;
        }
        .kop-text {
            text-align: center;
        }
        .kop-yayasan {
            font-size: 10.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .kop-univ {
            font-size: 13.5pt;
            font-weight: bold;
            letter-spacing: 0.8px;
            margin: 2px 0;
            color: #00295f;
            text-transform: uppercase;
        }
        .kop-lppm {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 2px 0;
            text-transform: uppercase;
        }
        .kop-alamat {
            font-size: 8pt;
            font-style: italic;
            margin: 2px 0 0 0;
            color: #222;
        }

        .header-meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .header-meta-table td {
            vertical-align: top;
            font-size: 11pt;
        }
        .info-col {
            width: 58%;
        }
        .date-col {
            width: 42%;
            text-align: right;
            padding-bottom: 8px;
        }

        .tujuan-block {
            margin-bottom: 14px;
            line-height: 1.35;
            font-size: 11pt;
        }

        .content-body {
            text-align: justify;
            margin-bottom: 12px;
            font-size: 11pt;
        }
        .content-body p {
            margin: 0 0 10px 0;
        }

        .table-data-peneliti {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px 0;
        }
        .table-data-peneliti td {
            padding: 2.5px 4px;
            vertical-align: top;
            font-size: 11pt;
        }

        .tabel-kolektif {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 12px 0;
        }
        .tabel-kolektif th,
        .tabel-kolektif td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 10pt;
            vertical-align: top;
        }
        .tabel-kolektif th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
        }

        .signature-table {
            width: 100%;
            margin-top: 18px;
            border-collapse: collapse;
        }
        .signature-table td {
            vertical-align: top;
        }
        .signature-box {
            float: right;
            width: 260px;
            text-align: left;
            font-size: 11pt;
            line-height: 1.35;
        }
        .qr-wrapper {
            margin: 6px 0;
            display: inline-block;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Resmi -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(!empty($logoBase64))
                    <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo Mahardika">
                @else
                    <img src="{{ public_path('mahardika-logo.png') }}" alt="Logo Mahardika">
                @endif
            </td>
            <td class="kop-text">
                <p class="kop-yayasan">YAYASAN INFORMATIKA MAHARDIKA</p>
                <h1 class="kop-univ">UNIVERSITAS INSAN MAHARDIKA</h1>
                <p class="kop-lppm">Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)</p>
                <p class="kop-alamat">Jl. Kesambi No. 202, Kesambi, Kota Cirebon, Jawa Barat 45134 | Email: lppm@mahardika.ac.id | Web: lppm.mahardika.ac.id</p>
            </td>
        </tr>
    </table>

    @php
        $jenisLower = strtolower($surat->jenis_surat ?? '');
        $isStudiPendahuluan = str_contains($jenisLower, 'studi pendahuluan') || str_contains($jenisLower, 'pendahuluan');
        $isIjinPenelitian = str_contains($jenisLower, 'ijin penelitian') || str_contains($jenisLower, 'izin penelitian');

        if ($isStudiPendahuluan) {
            $perihal = 'Permohonan Ijin Studi Pendahuluan';
        } elseif ($isIjinPenelitian) {
            $perihal = 'Permohonan Ijin Penelitian';
        } else {
            $perihal = $surat->jenis_surat;
        }

        $lokasiPenelitian = !empty($surat->lokasi_penelitian) ? $surat->lokasi_penelitian : $surat->instansi_tujuan;

        $tglSurat = $surat->approved_at 
            ? \Carbon\Carbon::parse($surat->approved_at)->translatedFormat('d F Y') 
            : ($surat->created_at ? $surat->created_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y'));
    @endphp

    <!-- 1. HEADER (TANGGAL KANAN & NOMOR KIRI) -->
    <table class="header-meta-table">
        <tr>
            <td class="info-col">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 75px;">Nomor</td>
                        <td style="width: 15px; text-align: center;">:</td>
                        <td><strong>{{ $surat->nomor_surat ?? '0108/UIM/VIII/2026' }}</strong></td>
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
            <td class="date-col">
                Cirebon, {{ $tglSurat }}
            </td>
        </tr>
    </table>

    <!-- Kepada Yth -->
    <div class="tujuan-block">
        <div>Kepada Yth :</div>
        <div><strong>{{ $surat->instansi_tujuan }}</strong></div>
        <div>di</div>
        <div style="padding-left: 24px;">Tempat</div>
    </div>

    <!-- 2. ISI SURAT -->
    <div class="content-body">
        @if($isStudiPendahuluan)
            <!-- TEMPLATE STUDI PENDAHULUAN -->
            <p style="margin-bottom: 12px;">
                Assalamualaikum Wr.Wb
            </p>

            <p>
                Dalam rangka proses penyusunan Penelitian Dosen Universitas Insan Mahardika Kota Cirebon, bersama ini kami mengajukan permohonan Peneliti tersebut dibawah ini.
            </p>

            <table class="table-data-peneliti">
                <tr>
                    <td style="width: 150px;">Nama Dosen</td>
                    <td style="width: 15px; text-align: center;">:</td>
                    <td><strong>{{ $surat->nama_dosen }}</strong></td>
                </tr>
                <tr>
                    <td>Judul Penelitian</td>
                    <td style="text-align: center;">:</td>
                    <td>{{ $surat->judul_riset ?? $surat->keperluan }}</td>
                </tr>
            </table>

            <p style="margin-top: 12px;">
                diberi ijin melaksanakan Penelitian di {{ $lokasiPenelitian }}. Demikian agar menjadi maklum. Atas perhatian serta kerjasamanya, kami ucapkan terima kasih.
            </p>

            <p style="margin-top: 12px;">
                Wa'alaikum Salam Warohmatullah Wabarokatuh.
            </p>

        @else
            <!-- TEMPLATE PERMOHONAN IJIN PENELITIAN -->
            <p>
                Dalam rangka proses penyusunan Penelitian Dosen Universitas Insan Mahardika Kota Cirebon, bersama ini kami mengajukan permohonan Peneliti tersebut dibawah ini.
            </p>

            <table class="tabel-kolektif">
                <thead>
                    <tr>
                        <th style="width: 7%;">No</th>
                        <th style="width: 38%; text-align: left;">Nama</th>
                        <th style="width: 55%; text-align: left;">Judul Penelitian</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $listPeneliti = $surat->anggota;
                        if (empty($listPeneliti)) {
                            $listPeneliti = [
                                ['nama' => $surat->nama_dosen, 'judul' => $surat->judul_riset ?? $surat->keperluan]
                            ];
                        }
                    @endphp
                    @foreach($listPeneliti as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}.</td>
                        <td>
                            <strong>{{ is_array($item) ? ($item['nama'] ?? '') : ($item->nama ?? $item) }}</strong>
                        </td>
                        <td>
                            {{ is_array($item) ? ($item['judul'] ?? '') : ($item->judul ?? '') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p style="margin-top: 12px;">
                diberi ijin melaksanakan Penelitian di {{ $lokasiPenelitian }}. Demikian agar menjadi maklum. Atas perhatian serta kerjasamanya, kami ucapkan terima kasih.
            </p>
        @endif
    </div>

    <!-- 3. TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td width="55%"></td>
            <td width="45%">
                <div class="signature-box">
                    @if($isStudiPendahuluan)
                        <div>an.Rektor</div>
                        <div style="margin-bottom: 2px;">Wakil Rektor I</div>

                        @if(!empty($qrCodeBase64))
                        <div class="qr-wrapper">
                            <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" width="72" height="72" alt="QR Code TTD">
                        </div>
                        @else
                        <div style="height: 55px;"></div>
                        @endif

                        <div><strong>Nonok Karlina, M.Kep., Ns., Sp.Kep.MB</strong></div>
                        <div>NIK. 2.1.07.02.023</div>
                    @else
                        <div style="margin-bottom: 2px;">Rektor</div>

                        @if(!empty($qrCodeBase64))
                        <div class="qr-wrapper">
                            <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" width="72" height="72" alt="QR Code TTD">
                        </div>
                        @else
                        <div style="height: 55px;"></div>
                        @endif

                        <div><strong>Dr. Yani Kamasturyani, SKM., M.H.Kes.</strong></div>
                        <div>NIK. 2.0.14.00.163</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
