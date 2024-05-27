<?php

namespace App\Http\Controllers;

use App\Actions\Keluhan\NotifyPelanggan;
use App\Actions\Keluhan\ReplyKeluhan;
use App\Http\Requests\StoreBalasanRequest;
use App\Models\Balasan;
use App\Models\Keluhan;
use App\Models\Notifikasi;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class KeluhanController extends Controller
{
    public function index(): View
    {
        return view('keluhan.list-keluhan');
    }

    // public function keluhanList(Request $request)
    // {
    //     $data = Keluhan::query()
    //         ->when(
    //             $request->user()->role->permissions()->where('name', 'Tambah Keluhan')->exists(),
    //             fn (Builder $query) => $query->where('id_pelanggan', $request->user()->id),
    //         )->latest()
    //         ->get();

    //     return DataTables::of($data)
    //         ->addIndexColumn()
    //         ->make(true);

    //     if ($request->ajax()) {
    //     }
    // }

    public function keluhanList(Request $request)
    {
        $data = Keluhan::with('balasan')
            ->when(
                $request->user()->role->permissions()->where('name', 'Tambah Keluhan')->exists(),
                fn (Builder $query) => $query->where('id_pelanggan', $request->user()->id)
            )
            ->latest()
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            // ->addColumn('balasan_petugas', function ($keluhan) {
            //     return $keluhan->balasan->map(fn (Balasan $balasan) => $balasan->balasan);
            // })
            ->rawColumns(['balasan_petugas']) // Allow HTML rendering
            ->make(true);
    }

    public function tambah(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'keluhan' => 'required',
            'gambar' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $path = 'images/info/';

        $gambar = uploads($request->gambar, $path);

        Keluhan::create([
            'id_pelanggan' => $request->user()->id,
            'nama' => $request->user()->nama,
            'tlp' => $request->user()->tlp,
            'alamat' => $request->user()->alamat,
            'keluhan' => $request->keluhan,
            'gambar' => $gambar,
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
                'gambar' => 'nullable',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $update = [
                'keluhan' => $request->keluhan,
            ];

            $keluhan = Keluhan::find($id);

            if ($request->gambar) {
                $path = 'images/info/';

                $gambar = uploads($request->gambar, $path);
                $update['gambar'] = $gambar;

                if (Storage::disk('public')->exists($path.$keluhan->gambar)) {
                    Storage::disk('public')->delete($path.$keluhan->gambar);
                }
            }

            $keluhan->update($update);

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

    public function balas(StoreBalasanRequest $request, Keluhan $keluhan): RedirectResponse
    {
        $keluhan->load('user');

        Pipeline::send($request)
            ->through([
                ReplyKeluhan::class,
                NotifyPelanggan::class,
            ])->thenReturn();

        return back()->with('success', 'Berhasil mengirim balasan keluhan');
    }
}
