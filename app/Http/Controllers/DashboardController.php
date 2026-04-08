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

    public function data(Request $request)
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
            );

        // 🔥 FILTER 1 TANGGAL
        if ($request->tanggal) {
            $rekap->whereDate('tanggal_kirimans.tanggal', $request->tanggal);
        }

        $rekap->groupBy('tanggal_kirimans.tanggal', 'produks.id', 'produks.nama_produk')
            ->orderBy('tanggal_kirimans.tanggal', 'desc');

        return datatables()
            ->of($rekap)
            ->addIndexColumn()
            ->editColumn('total_berat', function ($data) {
                return number_format($data->total_berat, 2);
            })
            ->rawColumns(['tanggal'])
            ->make(true);
    }
}
