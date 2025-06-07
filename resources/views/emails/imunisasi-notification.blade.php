<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Jadwal Imunisasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pengingat Jadwal Imunisasi Balita Anda</h1>
        <p>Halo Orang Tua/Wali dari **{{ $namaBalita }}**,</p>
        <p>Ini adalah pengingat untuk jadwal imunisasi balita Anda:</p>
        <ul>
            <li>Jenis Vaksin: <strong>{{ $jenisVaksin }}</strong></li>
            <li>Tanggal Imunisasi: <strong>{{ $tanggalImunisasi }}</strong></li>
        </ul>
        <p>Mohon datang tepat waktu ke posyandu/fasilitas kesehatan terdekat untuk imunisasi ini.</p>
        <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
        <p>Terima kasih atas perhatian Anda.</p>
        <p>Salam Hormat,</p>
        <p>Tim Imunisasi SIMBA</p>
        <div class="footer">
            <p>Pesan ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
