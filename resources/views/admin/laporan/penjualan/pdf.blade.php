<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fb;
            color: #333;
        }

        .container {
            width: 80%;
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }

        .header h3 {
            font-size: 28px;
            color: #333;
            font-weight: 600;
        }

        .header p {
            font-size: 16px;
            color: #777;
            margin-top: 5px;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            font-size: 14px;
            color: #555;
        }

        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .total-omzet {
            text-align: right;
            font-size: 18px;
            margin-top: 25px;
            font-weight: 600;
        }

        .highlight {
            color: #28a745;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #aaa;
        }

        .footer p {
            margin: 0;
        }

        .date {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h3>Laporan Penjualan</h3>
            {{--  <p class="date">Periode:
                {{ request('tanggal') ? 'Tanggal: ' . request('tanggal') : 'Bulan: ' . request('bulan') . ' Tahun: ' . request('tahun') }}
            </p>  --}}

            <p class="date">Periode: {{ $tanggal ? 'Tanggal: ' . $tanggal : 'Bulan: ' . $bulan . ' Tahun: ' . $tahun }}
            </p>

        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Total Harga</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $penjualan)
                        @foreach ($penjualan->penjualanDetail as $detail)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $detail->produk->kode_produk }}</td>
                                <td>{{ $detail->produk->nama_produk }}</td>
                                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-omzet">
            {{--  <p>Total Omzet: <span class="highlight">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</span></p>  --}}
        </div>

        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</p>
        </div>
    </div>

</body>

</html>
