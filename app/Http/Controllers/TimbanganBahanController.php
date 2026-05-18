<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\TanggalBahan;
use App\Models\TimbanganBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapBahanExport;
use Maatwebsite\Excel\Facades\Excel;

class TimbanganBahanController extends Controller
{
    public function index($id)
    {
        $tanggalBahan = TanggalBahan::findOrFail($id);

        $bahans = Bahan::all();

        return view('timbanganbahan.index', compact(
            'tanggalBahan',
            'bahans'
        ));
    }


    public function load($id)
    {
        $data = TimbanganBahan::with('bahan')
            ->where('tanggal_bahan_id', $id)
            ->orderBy('id')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_bahan_id' => 'required|exists:tanggal_bahans,id',
            'pcs' => 'nullable|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
        ]);

        // ambil urutan terakhir
        $lastUrutan = TimbanganBahan::where('tanggal_bahan_id', $request->tanggal_bahan_id)
            ->max('urutan');

        $data = TimbanganBahan::create([
            'tanggal_bahan_id' => $request->tanggal_bahan_id,
            'bahan_id' => 1, // default bahan_id = 1
            'pcs' => $request->pcs ?? 0,
            'berat' => $request->berat ?? 0,
            'urutan' => ($lastUrutan ?? 0) + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = TimbanganBahan::findOrFail($id);

        $data->update([
            'bahan_id' => $request->bahan_id,
            'pcs' => $request->pcs,
            'berat' => $request->berat,
        ]);

        // reload relasi bahan terbaru
        $data->load('bahan');

        return response()->json([
            'success' => true,
            'data' => $data,
            'kode' => $data->bahan->kode ?? 'putih'
        ]);
    }



    public function destroy($id)
    {
        $tanggalBahan = TimbanganBahan::find($id);
        $tanggalBahan->delete();

        return response()->json('Data berhasil dihapus', 200);
    }

    public function export($id)
    {
        return Excel::download(
            new RekapBahanExport($id),
            'Tally_Bahan_' . date('Ymd') . '.xlsx'
        );
    }
}
