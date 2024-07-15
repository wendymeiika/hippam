<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengumumanController extends Controller
{
    public function index()
    {
        try {
            return view('pengumuman.list-pengumuman');
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function list(Request $request)
    {
        $data = Pengumuman::orderBy('created_at', 'desc')->get();
    
        return response()->json($data, 200);
    }
    
    

    public function tambah(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'deskripsi' => 'required',
                'poster' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $path = 'images/info/';
            $poster = uploads($request->poster, $path);
    
            $pengumuman = Pengumuman::create([
                'id_petugas' => Auth::user()->id,
                'poster' => $poster,
                'deskripsi' => $request->deskripsi,
            ]);
    
            return response()->json(['success' => 'Berhasil menambahkan Pengumuman', 'pengumuman' => $pengumuman], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan Pengumuman', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'deskripsi' => 'required',
                'poster' => 'nullable',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $update = [
                'deskripsi' => $request->deskripsi,
            ];

            $info = Pengumuman::find($id);

            if ($request->poster) {
                $path = 'images/info/';

                $poster = uploads($request->poster, $path);
                $update['poster'] = $poster;

                if (Storage::disk('public')->exists($path.$info->poster)) {
                    Storage::disk('public')->delete($path.$info->poster);
                }
            }

            $info->update($update);

            return response()->json(['message' => 'Berhasil memperbarui Pengumuman', 'data' => $info], 200);
        } catch (Exception $e) {
            // Tangani error dan kembalikan respons JSON dengan status error
            return response()->json(['error' => 'Gagal memperbarui Pengumuman', 'message' => $e->getMessage()], 500);
        }
    }
    

    public function delete($id)
    {
        try {
            $info = Pengumuman::find($id);
            Pengumuman::destroy($id);

            $path = 'images/info/';

            if (Storage::disk('public')->exists($path.$info->poster)) {
                Storage::disk('public')->delete($path.$info->poster);
            }

            return response()->json('success', 200);
        } catch (Exception $e) {
            return view('error');
        }
    }
}