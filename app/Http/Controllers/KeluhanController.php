<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\Notifikasi;
use Auth;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Validator;

class KeluhanController extends Controller
{
    public function index()
    {
        try {
            return view('keluhan.list-keluhan');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function keluhanList(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->role == 'petugas') {
                $data = Keluhan::orderBy('created_at', 'desc')->get();
            } else {
                $data = Keluhan::where('id_pelanggan', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'keluhan' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            Keluhan::create([
                'id_pelanggan' => Auth::user()->id,
                'nama' => Auth::user()->nama,
                'tlp' => Auth::user()->tlp,
                'alamat' => Auth::user()->alamat,
                'keluhan' => $request->keluhan,
            ]);

            Notifikasi::create([
                'id_pelanggan' => Auth::user()->id,
                'nama' => Auth::user()->nama,
                'tlp' => Auth::user()->tlp,
                'type' => 'keluhan',
                'pesan' => 'Keluhan baru, cek pada menu keluhan',
                'petugas' => 1,
            ]);

            return back()->with('success', 'Berhasil menambahkan Keluhan');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
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
                'id_pelanggan' => Auth::user()->id,
                'nama' => Auth::user()->nama,
                'tlp' => Auth::user()->tlp,
                'alamat' => Auth::user()->alamat,
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
