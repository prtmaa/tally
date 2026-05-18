<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function dataFrozen(Request $request)
    {
        $rekap = DB::table('timbangans')
            ->join('tujuan_produks', 'timbangans.tujuan_produk_id', '=', 'tujuan_produks.id')
            ->join('produks', 'tujuan_produks.produk_id', '=', 'produks.id')
            ->join('tujuans', 'tujuan_produks.tujuan_id', '=', 'tujuans.id')
            ->join('tanggal_kirimans', 'tujuans.tanggal_kiriman_id', '=', 'tanggal_kirimans.id')
            ->select(
                'tanggal_kirimans.tanggal',
                'produks.nama_produk',
                DB::raw('SUM(timbangans.pcs) as total_pcs'),
                DB::raw('SUM(timbangans.berat) as total_berat')
            )

            // 🔥 DEFAULT 2 JENIS
            ->whereIn('tanggal_kirimans.jenis', ['frozen', 'frozen tulang']);

        // 🔥 FILTER TANGGAL
        if ($request->tanggal) {
            $rekap->whereDate('tanggal_kirimans.tanggal', $request->tanggal);
        }

        $rekap->groupBy('tanggal_kirimans.tanggal', 'produks.id', 'produks.nama_produk')
            ->orderBy('tanggal_kirimans.tanggal', 'desc');

        return datatables()
            ->of($rekap)
            ->addIndexColumn()
            ->make(true);
    }

    public function dataFresh(Request $request)
    {
        $rekap = DB::table('timbangans')
            ->join('tujuan_produks', 'timbangans.tujuan_produk_id', '=', 'tujuan_produks.id')
            ->join('produks', 'tujuan_produks.produk_id', '=', 'produks.id')
            ->join('tujuans', 'tujuan_produks.tujuan_id', '=', 'tujuans.id')
            ->join('tanggal_kirimans', 'tujuans.tanggal_kiriman_id', '=', 'tanggal_kirimans.id')
            ->select(
                'tanggal_kirimans.tanggal',
                'produks.nama_produk',
                DB::raw('SUM(timbangans.pcs) as total_pcs'),
                DB::raw('SUM(timbangans.berat) as total_berat')
            )
            ->whereIn('tanggal_kirimans.jenis', ['fresh bsb', 'fresh tulang', 'fresh campuran']);

        if ($request->tanggal) {
            $rekap->whereDate('tanggal_kirimans.tanggal', $request->tanggal);
        }

        $rekap->groupBy('tanggal_kirimans.tanggal', 'produks.id', 'produks.nama_produk')
            ->orderBy('tanggal_kirimans.tanggal', 'desc');

        return datatables()
            ->of($rekap)
            ->addIndexColumn()
            ->make(true);
    }

    public function dataBahan(Request $request)
    {
        $rekap = DB::table('timbangan_bahans')
            ->join('bahans', 'timbangan_bahans.bahan_id', '=', 'bahans.id')
            ->join('tanggal_bahans', 'timbangan_bahans.tanggal_bahan_id', '=', 'tanggal_bahans.id')
            ->select(
                'tanggal_bahans.tanggal',
                'bahans.nama',
                DB::raw('SUM(timbangan_bahans.pcs) as total_pcs'),
                DB::raw('SUM(timbangan_bahans.berat) as total_berat')
            );

        if ($request->tanggal) {
            $rekap->whereDate('tanggal_bahans.tanggal', $request->tanggal);
        }

        $rekap->groupBy(
            'tanggal_bahans.tanggal',
            'bahans.id',
            'bahans.nama'
        )
            ->orderBy('tanggal_bahans.tanggal', 'desc');

        return datatables()
            ->of($rekap)
            ->addIndexColumn()
            ->make(true);
    }
}
