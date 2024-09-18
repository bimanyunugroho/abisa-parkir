<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karcis Parkir</title>
    <style>
        @page {
            size: 80mm 109mm;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 80mm;
            background-color: white;
        }
        .ticket {
            width: 100%;
        }
        .header {
            padding: 1.5mm;
            border-bottom: 1px solid #000;
        }
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header h2 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
        }
        .info {
            text-align: center;
            font-size: 8pt;
        }
        .info p {
            margin: 0;
            line-height: 1.2;
        }
        .details {
            padding: 2mm;
        }
        .details p {
            display: flex;
            justify-content: space-between;
            margin: 1mm 0;
            font-size: 9pt;
        }
        .details p span:first-child {
            font-weight: bold;
        }
        .qr-code {
            text-align: center;
            padding: 2mm;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .qr-code img {
            width: 40mm;
            height: 40mm;
        }
        .footer {
            text-align: center;
            font-size: 7pt;
            padding: 2mm;
        }
        .footer p {
            margin: 1mm 0;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <div class="header-row">
                <div class="info">
                    <h2>KARCIS PARKIR</h2>
                    <p><strong>{{ $perusahaan['nama_perusahaan'] }}</strong></p>
                </div>
            </div>
            <div class="header-row">
                <p style="font-size: 9px; text-align: center;">{{ $perusahaan['alamat'] }} | Telp: {{ $perusahaan['no_telp'] }} | Email: {{ $perusahaan['email'] }}</p>
            </div>
        </div>
        <div class="details">
            <p><span>No. Tiket:</span> <span>{{ $ticket_number }}</span></p>
            <p><span>Plat Nomor:</span> <span>{{ $plat_nomor }}</span></p>
            <p><span>Jenis Kendaraan:</span> <span>{{ $jenis_kendaraan }}</span></p>
            <p><span>Tanggal Masuk:</span> <span>{{ $tanggal_masuk }}</span></p>
            <p><span>Jam Masuk:</span> <span>{{ $jam_masuk }}</span></p>
        </div>
        <div class="qr-code">
            <img src="data:image/png;base64,{{ $qr_code }}" alt="QR Code">
        </div>
        <div class="footer">
            <p>Harap simpan karcis ini.</p>
            <p>Karcis yang hilang akan dikenakan denda.</p>
        </div>
    </div>
</body>
</html>