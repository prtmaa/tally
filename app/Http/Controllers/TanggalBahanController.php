<?php

namespace App\Http\Controllers;

use App\Models\TanggalBahan;
use Illuminate\Http\Request;

class TanggalBahanController extends Controller
{
    public function index()
    {
        return view('tanggalbahan.index');
    }

    public function data()
    {
        $tanggalbahan = TanggalBahan::with('user')->orderBy('created_at', 'desc')->get();

        return datatables()
            ->of($tanggalbahan)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($tanggalbahan) {
                return formatTanggalIndo($tanggalbahan->tanggal);
            })
            ->addColumn('user', function ($tanggalbahan) {
                return $tanggalbahan->user->name;
            })
            ->addColumn('aksi', function ($tanggalbahan) {

                $view = '<a href="' . route('timbanganbahan.index', $tanggalbahan->id) . '" 
                        class="btn btn-sm btn-success">
                        <i class="fa fa-eye"></i>
                        </a>';

                $edit = '';
                $delete = '';

                if ($tanggalbahan->isOwner()) {
                    $edit = '<button type="button"
                            onclick="editForm(`' . route('tanggalbahan.update', $tanggalbahan->id) . '`)"
                            class="btn btn-sm btn-info btn-flat">
                            <i class="fa fa-pen"></i>
                            </button>';

                    $delete = '<button type="button"
                            onclick="deleteData(`' . route('tanggalbahan.destroy', $tanggalbahan->id) . '`)"
                            class="btn btn-sm btn-danger btn-flat">
                            <i class="fa fa-trash"></i>
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
        $tanggalbahan = new TanggalBahan();
        $tanggalbahan->tanggal = $request->tanggal;
        $tanggalbahan->user_id = auth()->id();
        $tanggalbahan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $tanggalbahan = TanggalBahan::find($id);

        return response()->json($tanggalbahan);
    }

    public function update(Request $request, $id)
    {
        $tanggalbahan = TanggalBahan::find($id);
        $tanggalbahan->tanggal = $request->tanggal;
        $tanggalbahan->update();

        return response()->json('Data berhasil diubah', 200);
    }

    public function destroy($id)
    {
        $tanggalbahan = TanggalBahan::find($id);
        $tanggalbahan->delete();

        return response()->json('Data berhasil dihapus', 200);
    }
}
