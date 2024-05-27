<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PengumumanController extends Controller
{
    public function index()
    {
        try {
            return view('pengumuman.list-pengumuman');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Pengumuman::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'deskripsi' => 'required',
                'poster' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $path = 'images/info/';

            $poster = uploads($request->poster, $path);

            Pengumuman::create([
                'id_petugas' => Auth::user()->id,
                'poster' => $poster,
                'deskripsi' => $request->deskripsi,
            ]);

            return back()->with('success', 'Berhasil menambahkan Pengumuman');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
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

            return back()->with('success', 'Berhasil memperbarui Pengumuman');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
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
