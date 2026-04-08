<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        return view('produk.index');
    }

    public function data()
    {
        $produk = Produk::orderBy('created_at', 'desc')->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('aksi', function ($produk) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('produk.update', $produk->id) . '`)" class="btn btn-sm btn-info btn-flat"><i class="fa fa-pen"></i></button>
                    <button type="button" onclick="deleteData(`' . route('produk.destroy', $produk->id) . '`)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'tanggal'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = new Produk();
        $produk->nama_produk = $request->nama;
        $produk->kode = $request->kode;
        $produk->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);
        $produk->nama_produk = $request->nama;
        $produk->kode = $request->kode;
        $produk->update();

        return response()->json('Data berhasil diubah', 200);
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return response()->json('Data berhasil dihapus', 200);
    }
}
