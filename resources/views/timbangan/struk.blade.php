<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            width: 70mm;
            font-size: 10px;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            width: 70mm;
            margin: 0 auto;
            padding: 2px 0;
        }

        .line {
            border-top: 1px dashed black;
            margin: 6px 0;
        }

        table td.tujuan-utama {
            font-weight: 900 !important;
            font-size: 18px !important;
            color: #000 !important;
            line-height: 0.9;
            text-align: center;
        }

        p {
            font-size: 10px;
        }

        table {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
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

        @foreach ($groups as $rak => $items)
            <div class="page-break">
                @php
                    $total_pcs = 0;
                    $total_berat = 0;
                @endphp
                <table>
                    <tr>

                        <td colspan="2" class="tujuan-utama center">Rak {{ $rak ?? '-' }}</td>

                    <tr>
                        <td>Prod. Date : {{ $data->prod_date ?? '-' }}</td>
                        <td align="right">
                            Tgl : {{ date('d-m-Y', strtotime($data->created_at)) }}
                        </td>
                    </tr>
                </table>
                <div class="line"></div>

                <table>
                    <tr>
                        <td colspan="2" class="tujuan-utama center">{{ $data->produk->nama_produk }}</td>
                    </tr>


                </table>

                <div class="line"></div>

                <table>

                    @foreach ($items as $t)
                        {{-- <tr>
                            <td colspan="2" class="right"></td>
                        </tr> --}}

                        <tr>
                            <td>{{ $t->urutan }}</td>
                            <td align="right">Pcs : {{ $t->pcs }}</td>
                        </tr>

                        <tr>
                            <td>{{ $t->seri }}</td>
                            <td align="right">Berat : {{ number_format($t->berat, 2) }} Kg</td>
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
