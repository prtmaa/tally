<?php

namespace App\Http\Controllers;

use App\Models\TanggalKiriman;
use App\Models\Tujuan;
use Illuminate\Http\Request;

class TujuanController extends Controller
{
    public function index($id)
    {
        $tanggal = TanggalKiriman::findOrFail($id);

        return view('tujuan.index', compact('id', 'tanggal'));
    }

    public function data($id)
    {
        $tujuan = Tujuan::where('tanggal_kiriman_id', $id)->get();

        return datatables()
            ->of($tujuan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($tujuan) {

                $view = '<a href="' . route('timbangan.index', $tujuan->id) . '" 
            class="btn btn-sm btn-success">
            <i class="fa fa-eye"></i>
            </a>';

                $edit = '';
                $delete = '';

                // MASTER
                if (auth()->user()->role == 'Master') {

                    $edit = '<button type="button"
                onclick="editForm(`' . route('tujuan.update', $tujuan->id) . '`)"
                class="btn btn-sm btn-info btn-flat">
                <i class="fa fa-pen"></i>
                </button>';

                    $delete = '<button type="button"
                onclick="deleteData(`' . route('tujuan.destroy', $tujuan->id) . '`)"
                class="btn btn-sm btn-danger btn-flat">
                <i class="fa fa-trash"></i>
                </button>';
                }

                // USER hanya bisa edit miliknya sendiri
                elseif (
                    auth()->user()->role == 'User' &&
                    $tujuan->isOwner()
                ) {

                    $edit = '<button type="button"
                onclick="editForm(`' . route('tujuan.update', $tujuan->id) . '`)"
                class="btn btn-sm btn-info btn-flat">
                <i class="fa fa-pen"></i>
                </button>';
                }

                return '
        <div class="btn-group">
            ' . $view . '
            ' . $edit . '
            ' . $delete . '
        </div>
    ';
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $tujuan = new Tujuan();
        $tujuan->nama_tujuan = $request->nama_tujuan;
        $tujuan->tanggal_kiriman_id = $request->tanggal_kiriman_id;
        $tujuan->prod_date_1 = $request->prod_date_1;
        $tujuan->prod_date_2 = $request->prod_date_2;
        $tujuan->prod_date_3 = $request->prod_date_3;
        $tujuan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $tujuan = Tujuan::find($id);

        return response()->json($tujuan);
    }

    public function update(Request $request, $id)
    {
        $tujuan = Tujuan::find($id);
        $tujuan->nama_tujuan = $request->nama_tujuan;
        $tujuan->prod_date_1 = $request->prod_date_1;
        $tujuan->prod_date_2 = $request->prod_date_2;
        $tujuan->prod_date_3 = $request->prod_date_3;
        $tujuan->update();

        return response()->json('Data berhasil diubah', 200);
    }

    public function destroy($id)
    {
        $tujuan = Tujuan::find($id);
        $tujuan->delete();

        return response()->json('Data berhasil dihapus', 200);
    }
}
