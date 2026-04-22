<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index()
    {
        return view('bahan.index');
    }

    public function data()
    {
        $bahan = Bahan::orderBy('created_at', 'desc')->get();

        return datatables()
            ->of($bahan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($bahan) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('bahan.update', $bahan->id) . '`)" class="btn btn-sm btn-info btn-flat"><i class="fa fa-pen"></i></button>
                    <button type="button" onclick="deleteData(`' . route('bahan.destroy', $bahan->id) . '`)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'tanggal'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $bahan = new Bahan();
        $bahan->nama = $request->nama;
        $bahan->kode = $request->kode;
        $bahan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $bahan = Bahan::find($id);

        return response()->json($bahan);
    }

    public function update(Request $request, $id)
    {
        $bahan = Bahan::find($id);
        $bahan->nama = $request->nama;
        $bahan->kode = $request->kode;
        $bahan->update();

        return response()->json('Data berhasil diubah', 200);
    }

    public function destroy($id)
    {
        $bahan = Bahan::find($id);
        $bahan->delete();

        return response()->json('Data berhasil dihapus', 200);
    }
}
