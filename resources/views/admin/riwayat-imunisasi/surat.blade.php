<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Imunisasi</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 50px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h3 {
            margin: 0;
        }
        .content {
            font-size: 16px;
            line-height: 1.7;
        }
        .info-table {
            margin-left: 30px;
            margin-top: 10px;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
        }
        .stamp {
            margin-top: 80px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h3>KLINIK BIDAN SUSAN</h3>
        <p><em>Jl. Pahlawan IV, Kecamatan Indramayu, Kabupaten Indramayu</em></p>
        <hr>
        <h4><u>SURAT KETERANGAN TELAH MELAKUKAN IMUNISASI</u></h4>
        <p>Nomor: 045/SK-IMUN/{{ $riwayat->id }}/{{ now()->format('Y') }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

        <table class="info-table">
            <tr>
                <td width="160px">Nama Balita</td>
                <td>: {{ $riwayat->balita->nama }}</td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td>: {{ \Carbon\Carbon::parse($riwayat->balita->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td>: 
                    @if($riwayat->balita && $riwayat->balita->orangtua)
                        {{ $riwayat->balita->orangtua->nama }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Jenis Imunisasi</td>
                <td>: {{ $riwayat->jenis_imunisasi }}</td>
            </tr>
            <tr>
                <td>Tanggal Imunisasi</td>
                <td>: {{ \Carbon\Carbon::parse($riwayat->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">
            Telah melakukan imunisasi sesuai dengan jadwal yang telah ditentukan. Surat ini diberikan sebagai bukti pelaksanaan imunisasi.
        </p>
    </div>

    <div class="signature">
        <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Hormat Kami,</p>
        <div class="stamp">
            <p><strong>Klinik Bidan Susan</strong></p>
            <br><br><br>
            <p><u>(__________________)</u></p>
        </div>
    </div>

</body>
</html>
