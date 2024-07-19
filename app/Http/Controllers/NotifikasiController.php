<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('notifikasi.list-notifikasi');
    }

    public function list(Request $request)
    {
        $data = Notifikasi::query()
            ->when(
                $request->user()->role->name === 'petugas',
                fn (Builder $query) => $query->where('petugas', 1),
                fn (Builder $query) => $query->where('id_pelanggan', $request->user()->id)
                    ->where('petugas', false)
            )->latest()
            ->get();

        // Set it already read
        Notifikasi::query()->whereIn('id', $data->pluck('id'))->update(['read' => 1]);

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function destroy($id)
    {
    $notifikasi = Notifikasi::find($id);
    if ($notifikasi) {
        $notifikasi->delete();
        return response()->json(['success' => 'Notifikasi berhasil dihapus.']);
    }
    return response()->json(['error' => 'Notifikasi tidak ditemukan.'], 404);
    }

}
