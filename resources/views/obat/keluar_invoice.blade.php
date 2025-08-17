<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <title>Resep Obat #{{ $keluar->id }}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 0 30px;
            }

            header {
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }

            h1 {
                margin: 0;
            }

            .header-left {
                float: left;
            }

            .header-right {
                float: right;
                text-align: right;
            }

            .clear {
                clear: both;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
            }

            th {
                background: #f2f2f2;
            }

            .total {
                font-weight: bold;
            }

            footer {
                border-top: 2px solid #000;
                padding-top: 10px;
                font-size: 10px;
                text-align: center;
                color: #555;
            }
        </style>
    </head>

    <body>
        <header>
            <div class="header-left">
                <h1>Resep Obat</h1>
                @php
                    use Carbon\Carbon;
                @endphp

                <div>Tanggal: {{ Carbon::parse($keluar->tanggal_keluar)->format('d-m-Y') }}</div>
                <div>Pasien: {{ $keluar->pasien->nama ?? '-' }}</div>
            </div>
            <div class="header-right">
                <div>ID Transaksi: #{{ $keluar->id }}</div>
                <div>Poliklinik: Poli XYZ</div>
            </div>
            <div class="clear"></div>
        </header>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Obat</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($keluar->detail as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->obat->nama_obat }}</td>
                        <td>{{ $detail->obat->satuan }}</td>
                        <td>{{ $detail->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <footer>
            <div>Terima kasih telah mempercayakan resep obat Anda kepada kami.</div>
            <div>PT Klinik Sehat Selalu - Jl. Kesehatan No. 123, Kota Sehat</div>
            <div>Telp: (021) 123-4567 | Email: info@kliniksehat.com</div>
        </footer>
    </body>

</html>
