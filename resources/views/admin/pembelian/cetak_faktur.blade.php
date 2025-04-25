<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Faktur Pembelian</title>
    <style>
        @page {
            size: 21.5cm 14cm;
            margin: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        .no-border {
            border: none;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 30%;
            text-align: center;
        }
    </style>
</head>

<body>
    <table style="width:100%; border-collapse: collapse; margin-bottom: 10px;">
        <thead>
            <tr>
                <th colspan="4" rowspan="3"
                    style="text-align: left; font-size: 18px; margin-top:0; font-weight: bold; border: none;">Faktur
                    Pembelian Tunai
                </th>
                <td style="text-align: left; border: none;">NO</td>
                <td style="text-align: left; border: none;">:</td>
                <td style="text-align: left; border: none;">{{ $pembelian->invoice_number }}</td>
            </tr>
            <tr>
                <td style="text-align: left; border: none;">Nama</td>
                <td style="text-align: left; border: none;">:</td>
                <td style="text-align: left; border: none;">{{ $pembelian->user->name }}</td>
            </tr>
            <tr>
                <td style="text-align: left; border: none;">Alamat</td>
                <td style="text-align: left; border: none;">:</td>
                <td style="text-align: left; border: none;">-</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="text-align: left; width: 10%; border: none;">Tanggal</td>
                <td style="text-align: left; border: none;">:</td>
                <td style="text-align: left; width: 50%; border: none;">21 April</td>

                <td style="text-align: left; width: 20%; border: none;">Sales</td>
                <td style="text-align: left; border: none;">:</td>
                <td style="text-align: left; width: 30%; border: none;">dfafas</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none;">
                    No</th>
                <th
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none;">
                    Kode</th>
                <th
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none;">
                    Nama Produk</th>
                <th class="center"
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none;">
                    Qty</th>
                <th class="center"
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none;">
                    Harga</th>
                <th class="center"
                    style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none;">
                    Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data produk bisa diisi di sini -->
            @foreach ($pembelian->pembelianDetail as $item)
                <tr>
                    <td class="no-border">{{ $loop->iteration }}</td>
                    <td class="no-border">{{ $item->produk->kode_produk }}</td>
                    <td class="no-border">{{ $item->produk->nama_produk ?? '' }}</td>
                    <td class="center no-border">{{ $item->jumlah }}</td>
                    <td class="center no-border">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="center no-border">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach


            <!-- Tambahkan baris lain sesuai kebutuhan -->
        </tbody>
    </table>
    <div class="footer">
        <table style="width: 100%; border-collapse: collapse;">
            <tbody>
                <tr>
                    <!-- Kolom Terbilang -->
                    <td
                        style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none; width: 5%;">
                        <strong>Terbilang</strong>
                    </td>
                    <!-- Kolom Titik Dua -->
                    <td
                        style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none; width: 2%; text-align: center;">
                        :
                    </td>
                    <!-- Kolom Teks Angka Terbilang -->
                    <th
                        style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none;">
                        {{--  Dua juta empat ratus empat puluh ribu Rupiah  --}}
                        {{ ucwords(terbilang($pembelian->total_harga)) }}

                    </th>
                    <!-- Kolom Angka -->
                    <td
                        style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none; text-align: center;">
                        <strong>Rp. {{ format_uang($pembelian->total_harga) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="text-align: center; margin-top: 50px;">
        <table style="width: 60%; margin: 0 auto; border-collapse: collapse;">
            <tr>
                <td style="border: none; text-align: center;">(...........................................)</td>
                <td style="border: none;text-align: center;">(...........................................)</td>
            </tr>
            <tr>
                <td style="border: none; text-align: center;">Penerima</td>
                <td style="border: none; text-align: center;">Pengirim</td>
            </tr>

        </table>
    </div>


    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>
