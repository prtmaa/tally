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
            /* ini yang membuat konten ke tengah */
            padding: 2px 0;
        }

        .title {
            text-align: center;
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed black;
            margin: 2px 0;
        }

        table {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
        }

        p {
            font-size: 10px;
        }
        
       table td.tujuan-utama {
        font-weight: 900 !important;
        font-size: 18px !important;
        color: #000 !important;
        line-height: 0.9;
        text-align: center;
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
    </style>

</head>

<body onload="window.print()">

    {{-- <body onload="window.print();window.close();"> --}}


    <div class="container">

  <table>
    <tr>
        <!-- Kolom 1: Kosong (sebagai penyeimbang) -->
        <td width="20%"></td> 
        
        <!-- Kolom 2: Tujuan (Tengah) -->
        <td class="tujuan-utama center">{{ $timbang->tujuanProduk->tujuan->nama_tujuan ?? '-' }}</td>
        
        <!-- Kolom 3: Urutan (Kanan) -->
        <td class="right" width="20%">{{ $timbang->urutan }}</td>
    </tr>
</table>
        <div class="line"></div>

        <table>

            <tr>
                <td>Produk</td>
                <td class="right">{{ $timbang->tujuanProduk->produk->nama_produk }}</td>
            </tr>

            <tr>
                <td>Prod. Date</td>
                <td class="right">{{ $timbang->tujuanProduk->prod_date ?? '-' }}</td>
            </tr>

            <tr>
                <td>No Seri</td>
                <td class="right">{{ $timbang->seri }}</td>
            </tr>

        </table>

        <div class="line"></div>

        <table>

            <tr>
                <td>PCS</td>
                <td class="right">{{ $timbang->pcs }}</td>
            </tr>

            <tr>
                <td>Berat</td>
                <td class="right">{{ number_format($timbang->berat, 2) }} Kg</td>
            </tr>

        </table>

        <div class="line"></div>

        <div class="center">
            {{ date('d-m-Y', strtotime($timbang->created_at)) }}
        </div>

    </div>

</body>

</html>
