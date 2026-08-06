<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Penjualan</title>
    <style>
        @page {
            size: 21cm 14cm;
            margin: 0.5cm;
        }

        body {
            font-family: 'Calibri', 'Helvetica', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            margin-bottom: 100px; /* Space for the fixed footer */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            padding: 2px 0;
        }

        .item-table th,
        .item-table td {
            border: 1px solid #000;
            padding: 4px 5px;
        }

        .item-table th {
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 90px;
        }

        .currency-symbol {
            float: left;
        }

        .currency-value {
            float: right;
        }
        
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Header -->
        <table class="header-table" style="margin-bottom: 10px;">
            <tr>
                <td style="width: 55%; vertical-align: top;">
                    <h2 style="margin: 0; font-size: 18px; text-transform: uppercase;">FAKTUR PENJUALAN TUNAI</h2>
                </td>
                <td style="width: 45%; vertical-align: top;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 25%;">NO</td>
                            <td style="width: 5%;">:</td>
                            <td><strong>{{ $penjualan->invoice_number }}</strong></td>
                        </tr>
                        <tr>
                            <td>Nama Toko</td>
                            <td>:</td>
                            <td>{{ $penjualan->customer->nama_toko }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ $penjualan->customer->alamat }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Tanggal & Sales -->
        <table class="header-table" style="margin-bottom: 10px;">
            <tr>
                <td style="width: 10%;">Tanggal</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">{{ tanggal_indonesia($penjualan->tanggal) }}</td>
                
                <td style="width: 10%;">Sales</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">{{ $penjualan->user->name }}</td>
            </tr>
        </table>

        <!-- Item Table -->
        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Kode</th>
                    <th style="width: 30%;">Nama Produk</th>
                    <th style="width: 8%;">Qty</th>
                    <th style="width: 20%;">Harga</th>
                    <th style="width: 22%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan->penjualanDetail as $item)
                    <tr>
                        <td class="center">{{ $loop->iteration }}</td>
                        <td>{{ $item->produk->kode_produk }}</td>
                        <td>{{ $item->produk->nama_produk ?? '' }}</td>
                        <td class="center">{{ $item->jumlah }}</td>
                        <td>
                            <span class="currency-symbol">Rp</span>
                            <span class="currency-value">{{ format_uang($item->harga) }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td>
                            <span class="currency-symbol">Rp</span>
                            <span class="currency-value">{{ format_uang($item->harga * $item->jumlah) }}</span>
                            <div class="clearfix"></div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="padding: 0;">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="width: 12%; border: none; padding: 5px;"><strong>Terbilang</strong></td>
                                <td style="width: 2%; border: none; padding: 5px;">:</td>
                                <td style="border: none; padding: 5px;"><strong>{{ ucwords(terbilang($penjualan->total_harga)) }} Rupiah</strong></td>
                                <td style="width: 25%; text-align: right; border: none; padding: 5px;">
                                    <strong>Rp {{ format_uang($penjualan->total_harga) }}</strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Fixed Footer for Signatures -->
    <div class="footer">
        <table style="width: 80%; margin: 0 auto; border: none;">
            <tr>
                <td style="border: none; text-align: center; width: 50%; height: 50px;"></td>
                <td style="border: none; text-align: center; width: 50%; height: 50px;"></td>
            </tr>
            <tr>
                <td style="border: none; text-align: center;">(...........................................)</td>
                <td style="border: none; text-align: center;">(...........................................)</td>
            </tr>
            <tr>
                <td style="border: none; text-align: center;">Penerima</td>
                <td style="border: none; text-align: center;">Pengirim</td>
            </tr>
        </table>
    </div>
</body>
</html>
