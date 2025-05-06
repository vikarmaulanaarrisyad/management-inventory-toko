<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Faktur Pembelian</title>
    <style>
        @page {
            size: 21cm 14cm;
            margin: 0.8cm;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Calibri', sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        .content {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 0px;
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
            margin-top: auto;
        }

        .signature {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 30%;
            text-align: center;
        }

        .terbilang {
            font-size: 20px;
            font-weight: bold;
            padding-left: 2px;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="content">
            <table style="width:100%; border-collapse: collapse; margin-bottom: 2px;">
                <thead>
                    <tr>
                        <th colspan="4" rowspan="3"
                            style="text-align: left; font-size: 18px;font-weight: bold; border: none;">
                            FAKTUR PEMBELIAN TUNAI
                        </th>
                        <td style="text-align: left; border: none;">NO</td>
                        <td style="text-align: left; border: none;  padding-right:5px;">: </td>
                        <td style="text-align: left; border: none;"><span
                                style="font-size: 18px !important;">{{ $pembelian->invoice_number }}</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; border: none;"></td>
                        <td style="text-align: left; border: none; padding-right:5px;"> </td>
                        <td style="text-align: left; border: none;"></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" style="text-align: left; width: 10%; border: none;">Tanggal</td>
                        <td style="text-align: center; border: none; padding-right: 20px;">:</td>

                        <td style="text-align: left; width: 40%; border: none;">
                            {{ tanggal_indonesia($pembelian->tanggal) }}
                        </td>

                        <td style="text-align: left; width: 20%; border: none;">Sales</td>
                        <td style="text-align: left; border: none;">: </td>
                        <td style="text-align: left; width: 30%; border: none;">{{ $pembelian->user->name }}</td>
                    </tr>
                </tbody>
            </table>

            {{--  <table style="width: 100%; border-collapse: collapse;">
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
                    @foreach ($pembelian->pembelianDetail as $item)
                        <tr>
                            <td class="no-border">{{ $loop->iteration }}</td>
                            <td class="no-border">{{ $item->produk->kode_produk }}</td>
                            <td class="no-border">{{ $item->produk->nama_produk ?? '' }}</td>
                            <td class="center no-border">{{ $item->jumlah }}</td>
                            <td class="right no-border">
                                <div style="display: flex; justify-content: flex-start;">
                                    <span style="width: 30px;">Rp</span>
                                    <span>{{ number_format($item->harga, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td class="right no-border">
                                <div style="display: flex; justify-content: flex-start;">
                                    <span style="width: 30px;">Rp</span>
                                    <span>{{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>  --}}

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
                    @foreach ($pembelian->pembelianDetail as $item)
                        <tr>
                            <td class="no-border">{{ $loop->iteration }}</td>
                            <td class="no-border">{{ $item->produk->kode_produk }}</td>
                            <td class="no-border">{{ $item->produk->nama_produk ?? '' }}</td>
                            <td class="center no-border">{{ $item->jumlah }}</td>
                            <td class="no-border">
                                <div style="display: flex; justify-content: space-between; width: 100%;">
                                    <span style="width: 10px; margin-left: 10px;">Rp</span>
                                    <span
                                        style="margin-left: 0px; text-align: right;">{{ number_format($item->harga, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td class="no-border">
                                <div style="display: flex; justify-content: space-between; width: 100%;">
                                    <span style="width: 10px;margin-left: 20px;">Rp</span>
                                    <span
                                        style="margin-left: 0px; text-align: right;">{{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>

        <div class="footer">
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td
                            style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none; width: 5%;">
                            <strong>Terbilang</strong>
                        </td>
                        <td
                            style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none; width: 2%; text-align: center;">
                            :
                        </td>
                        <td colspan="2"
                            style="border-top: 1px solid #000; border-bottom: 1px solid #000; border-left: none; border-right: none;">
                            <div class="terbilang">
                                {{ ucwords(terbilang($pembelian->total_harga)) }} Rupiah &nbsp;&nbsp;&nbsp;
                                <strong style="float: right;">Rp {{ format_uang($pembelian->total_harga) }}</strong>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

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
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

    <noscript>
        <div style="display: none;">Matikan "Headers and Footers" di pengaturan cetak browser untuk hasil yang bersih.
        </div>
    </noscript>
</body>

</html>
