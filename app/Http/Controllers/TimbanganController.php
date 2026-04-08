<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Timbangan;
use App\Models\Tujuan;
use App\Models\TujuanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapDoExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class TimbanganController extends Controller
{
    public function index($id)
    {
        $tujuan = Tujuan::findOrFail($id);

        $produk = Produk::all();

        $prodDates = [
            $tujuan->prod_date_1,
            $tujuan->prod_date_2
        ];

        return view('timbangan.index', compact('tujuan', 'produk', 'prodDates'));
    }

    public function load($id)
    {
        $data = TujuanProduk::with(['produk', 'timbangans'])
            ->where('tujuan_id', $id)
            ->get();

        return response()->json($data);
    }


    public function storeProduk(Request $request)
    {
        $tp = TujuanProduk::create([
            'tujuan_id' => $request->tujuan_id,
            'produk_id' => $request->produk_id,
            'prod_date' => $request->prod_date,
            'note' => $request->note
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $tp->load('produk')
        ]);
    }

    public function updateProduk(Request $request, $id)
    {
        $data = TujuanProduk::findOrFail($id);

        $data->update([
            'produk_id' => $request->produk_id,
            'prod_date' => $request->prod_date,
            'note' => $request->note
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }


    public function destroyProduk($id)
    {
        $data = TujuanProduk::findOrFail($id);

        $data->timbangans()->delete();

        $data->delete();

        return response()->json(['status' => 'success']);
    }

    public function store(Request $request)
    {
        $tujuanProduk = TujuanProduk::with('produk')
            ->findOrFail($request->tujuan_produk_id);

        $urutan = Timbangan::where('tujuan_produk_id', $request->tujuan_produk_id)
            ->max('urutan');

        $urutan = $urutan ? $urutan + 1 : 1;

        $tahun = date('ym');
        $produkId = $request->tujuan_produk_id;
        $kode = $tujuanProduk->produk->kode;
        $urutanFormat = str_pad($urutan, 2, '0', STR_PAD_LEFT);
        $seri = $tahun . $kode . $produkId . $urutanFormat;

        $data = Timbangan::create([
            'tujuan_produk_id' => $request->tujuan_produk_id,
            'pcs' => $request->pcs,
            'berat' => $request->berat,
            'urutan' => $urutan,
            'seri' => $seri
        ]);

        return response()->json([
            'data' => $data
        ]);
    }


    public function update(Request $request, $id)
    {
        $data = Timbangan::findOrFail($id);

        $data->update([
            'pcs' => $request->pcs,
            'berat' => $request->berat
        ]);

        return response()->json(['status' => 'success']);
    }


    public function destroy($id)
    {
        Timbangan::findOrFail($id)->delete();

        return response()->json(['status' => 'success']);
    }

    public function rekap($id)
    {
        $data = DB::table('tujuan_produks')
            ->join('produks', 'produks.id', '=', 'tujuan_produks.produk_id')
            ->leftJoin('timbangans', 'timbangans.tujuan_produk_id', '=', 'tujuan_produks.id')
            ->where('tujuan_produks.tujuan_id', $id)
            ->select(
                'produks.nama_produk',
                DB::raw('SUM(timbangans.pcs) as total_pcs'),
                DB::raw('SUM(timbangans.berat) as total_berat')
            )
            ->groupBy('produks.nama_produk')
            ->orderBy('produks.nama_produk')
            ->get();

        return response()->json($data);
    }

    public function export($id)
    {
        $tujuan = DB::table('tujuans')
            ->where('id', $id)
            ->first();

        return Excel::download(new RekapDoExport($id), 'DO_' . $tujuan->nama_tujuan . '_' . date('Ymd') . '.xlsx');
    }

    public function printStruk($tujuan_produk_id)
    {
        $data = TujuanProduk::with(['produk', 'timbangans', 'tujuan'])->findOrFail($tujuan_produk_id);
        $timbangans = $data->timbangans;
        $chunks = $timbangans->chunk(10);
        $pdf = Pdf::loadView('timbangan.struk', ['data' => $data, 'chunks' => $chunks])->setPaper([0, 0, 226.77, 950], 'portrait');
        return $pdf->stream("sampel.pdf");
    }

    public function printTimbangan($id)
    {
        $timbang = Timbangan::with(['tujuanProduk.produk', 'tujuanProduk.tujuan'])->findOrFail($id);
        $pdf = Pdf::loadView('timbangan.struk_satuan', compact('timbang'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream("struk.pdf");
    }
}
