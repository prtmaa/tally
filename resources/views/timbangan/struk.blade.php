<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
        }

        .container {
            width: 70mm;
            margin: 0 auto;
            padding: 5px 0;
        }

        .line {
            border-top: 1px dashed black;
            margin: 6px 0;
        }

        p {
            font-size: 10px;
        }

        table {
            width: 100%;
            font-size: 10px;
        }

        td {
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        @page {
            margin: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .page-break:last-child {
            page-break-after: auto;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container">

        @foreach ($chunks as $chunk)
            <div class="page-break">
                @php
                    $total_pcs = 0;
                    $total_berat = 0;
                @endphp

                <div class="line"></div>

                <table>
                    <tr>
                        <td>Produk</td>
                        <td align="right">{{ $data->produk->nama_produk }}</td>
                    </tr>

                    <tr>
                        <td>Prod. Date</td>
                        <td align="right">{{ $data->prod_date ?? '-' }}</td>
                    </tr>
                </table>

                <div class="line"></div>

                <table>

                    @foreach ($chunk as $t)
                        <tr>
                            <td colspan="2" class="right">{{ $t->urutan }}</td>
                        </tr>

                        <tr>
                            <td>No Seri</td>
                            <td align="right">{{ $t->seri }}</td>
                        </tr>

                        <tr>
                            <td>PCS</td>
                            <td align="right">{{ $t->pcs }}</td>
                        </tr>

                        <tr>
                            <td>Berat</td>
                            <td align="right">{{ number_format($t->berat, 2) }} Kg</td>
                        </tr>

                        <tr>
                            <td colspan="2" class="center">
                                {{ date('d-m-Y', strtotime($t->created_at)) }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <div class="line"></div>
                            </td>
                        </tr>

                        @php
                            $total_pcs += $t->pcs;
                            $total_berat += $t->berat;
                        @endphp
                    @endforeach

                </table>

                <table>
                    <tr>
                        <td><b>Total PCS</b></td>
                        <td align="right"><b>{{ $total_pcs }}</b></td>
                    </tr>

                    <tr>
                        <td><b>Total Berat</b></td>
                        <td align="right"><b>{{ number_format($total_berat, 2) }} Kg</b></td>
                    </tr>
                </table>

                <div class="line"></div>

            </div>
        @endforeach

    </div>

</body>

</html>
