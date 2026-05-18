<?php

namespace App\Http\Controllers;

use App\Models\TanggalKiriman;
use Illuminate\Http\Request;

class TanggalController extends Controller
{
    public function index()
    {
        return view('tally.index');
    }

    public function data()
    {
        $tanggal_kiriman = TanggalKiriman::with('user')->orderBy('tanggal', 'desc')->get();

        return datatables()
            ->of($tanggal_kiriman)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($row) {
                return formatTanggalIndo($row->tanggal);
            })
            ->addColumn('tanggal_sort', function ($row) {
                return $row->tanggal;
            })

            ->addColumn('user', function ($tanggal_kiriman) {
                return $tanggal_kiriman->jenis . " - " . $tanggal_kiriman->user->name;
            })
            ->addColumn('aksi', function ($tanggal_kiriman) {

                $view = '<a href="' . route('tujuan.index', $tanggal_kiriman->id) . '" 
            class="btn btn-sm btn-success">
            <i class="fa fa-eye"></i>
            </a>';

                $edit = '';
                $delete = '';

                // MASTER
                if (auth()->user()->role == 'Master') {

                    $edit = '<button type="button"
                onclick="editForm(`' . route('tally.update', $tanggal_kiriman->id) . '`)"
                class="btn btn-sm btn-info btn-flat">
                <i class="fa fa-pen"></i>
                </button>';

                    $delete = '<button type="button"
                onclick="deleteData(`' . route('tally.destroy', $tanggal_kiriman->id) . '`)"
                class="btn btn-sm btn-danger btn-flat">
                <i class="fa fa-trash"></i>
                </button>';
                }

                // USER hanya bisa edit miliknya sendiri
                elseif (
                    auth()->user()->role == 'User' &&
                    $tanggal_kiriman->isOwner()
                ) {

                    $edit = '<button type="button"
                onclick="editForm(`' . route('tally.update', $tanggal_kiriman->id) . '`)"
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

            ->rawColumns(['aksi', 'tanggal'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $tanggal_kiriman = new TanggalKiriman();
        $tanggal_kiriman->tanggal = $request->tanggal;
        $tanggal_kiriman->jenis = $request->jenis;
        $tanggal_kiriman->user_id = auth()->id();
        $tanggal_kiriman->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $tanggal_kiriman = TanggalKiriman::find($id);

        return response()->json($tanggal_kiriman);
    }

    public function update(Request $request, $id)
    {
        $tanggal_kiriman = TanggalKiriman::find($id);
        $tanggal_kiriman->tanggal = $request->tanggal;
        $tanggal_kiriman->jenis = $request->jenis;
        $tanggal_kiriman->update();

        return response()->json('Data berhasil diubah', 200);
    }

    public function destroy($id)
    {
        $tanggal_kiriman = TanggalKiriman::find($id);
        $tanggal_kiriman->delete();

        return response()->json('Data berhasil dihapus', 200);
    }
}
