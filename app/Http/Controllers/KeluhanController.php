<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\Notifikasi;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class KeluhanController extends Controller
{
    public function index(): View
    {
        return view('keluhan.list-keluhan');
    }

    public function keluhanList(Request $request)
    {
        $data = Keluhan::query()
            ->when(
                $request->user()->role->permissions()->where('name', 'Tambah Keluhan')->exists(),
                fn (Builder $query) => $query->where('id_pelanggan', $request->user()->id),
            )->latest()
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);

        if ($request->ajax()) {
        }
    }

    public function tambah(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'keluhan' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Keluhan::create([
            'id_pelanggan' => $request->user()->id,
            'nama' => $request->user()->nama,
            'tlp' => $request->user()->tlp,
            'alamat' => $request->user()->alamat,
            'keluhan' => $request->keluhan,
        ]);

        Notifikasi::create([
            'id_pelanggan' => $request->user()->id,
            'nama' => $request->user()->nama,
            'tlp' => $request->user()->tlp,
            'type' => 'keluhan',
            'pesan' => 'Keluhan baru, cek pada menu keluhan',
            'petugas' => 1,
        ]);

        return back()->with('success', 'Berhasil menambahkan Keluhan');
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'keluhan' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $keluhan = Keluhan::find($id);

            $keluhan->update([
                'id_pelanggan' => $request->user()->id,
                'nama' => $request->user()->nama,
                'tlp' => $request->user()->tlp,
                'alamat' => $request->user()->alamat,
                'keluhan' => $request->keluhan,
            ]);

            return back()->with('success', 'Berhasil memperbarui Keluhan');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Keluhan::destroy($id);

            return response()->json('success', 200);
        } catch (Exception $e) {
            return view('error');
        }
    }
}
