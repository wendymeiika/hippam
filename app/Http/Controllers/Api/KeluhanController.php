<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Balasan;
use App\Models\Keluhan;
use Illuminate\View\View;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Actions\Keluhan\ReplyKeluhan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Actions\Keluhan\NotifyPelanggan;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreBalasanRequest;

class KeluhanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['keluhanList']); // Tambahkan metode lain yang tidak perlu otentikasi di sini
    }

    public function index(): View
    {
        return view('keluhan.list-keluhan');
    }

    public function keluhanList(Request $request)
    {
        $data = Keluhan::with('balasan')
            ->when(
                $request->user()->role->permissions()->where('name', 'Tambah Keluhan')->exists(),
                fn (Builder $query) => $query->where('id_pelanggan', $request->user()->id)
            )
            ->latest()
            ->get();

        return response()->json($data, 200);
    }
    public function tambah(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'keluhan' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan validasi untuk gambar
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
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

        return response()->json(['success' => 'Berhasil menambahkan Keluhan'], 200);
    }


    public function update(Request $request, $id)
    {
        try {
            // Hapus semua validasi
            // $validator = Validator::make($request->all(), [
            //     'keluhan' => 'required',
            //     'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['error' => $validator->errors()], 422);
            // }

            $update = [
                'keluhan' => $request->keluhan,
            ];

            $keluhan = Keluhan::find($id);

            if (!$keluhan) {
                return response()->json(['error' => 'Keluhan not found'], 404);
            }

            if ($request->hasFile('gambar')) {
                $path = 'images/info/';
                $gambar = $request->file('gambar')->store($path, 'public');
                $update['gambar'] = $gambar;

                if (Storage::disk('public')->exists($path . $keluhan->gambar)) {
                    Storage::disk('public')->delete($path . $keluhan->gambar);
                }
            }

            $keluhan->update($update);

            return response()->json(['success' => 'Berhasil memperbarui Keluhan', 'keluhan' => $keluhan], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui Keluhan'], 500);
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

    public function balas(StoreBalasanRequest $request, Keluhan $keluhan): JsonResponse
    {
        $keluhan->load('user');

        Pipeline::send($request)
            ->through([
                ReplyKeluhan::class,
                NotifyPelanggan::class,
            ])->thenReturn();

        return response()->json(['success' => 'Berhasil mengirim balasan keluhan'], 200);
    }
}
