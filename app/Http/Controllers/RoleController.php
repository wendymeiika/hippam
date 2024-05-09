<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use DataTables;
use Exception;
use Hash;
use Validator;

class RoleController extends Controller
{
    public function index()
    {
        try {
            return view('role.index');
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function roleList(Request $request)
    {
        if ($request->ajax()) {
            // Definisikan daftar peran yang diinginkan
            $roles = ['admin', 'ketuart', 'petugas'];
    
            // Gunakan klausa whereIn untuk mencocokkan peran yang ada dalam daftar
            $data = User::whereIn('role', $roles)
                        ->orderBy('created_at', 'desc')
                        ->get();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    
    public function tambahRole(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required|unique:user',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'tlp' => 'required|numeric|unique:user',
                'role' => 'required|in:admin,ketuart,petugas', // Tambahkan validasi untuk peran
            ]);
    
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
    
            User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'tlp' => $request->tlp,
                'password' => Hash::make($request->tlp),
                'role' => $request->role, // Ambil nilai peran dari input form
            ]);
    
            return back()->with('success', 'Berhasil menambahkan pengguna dengan peran ' . $request->role);
        } catch (Exception $e) {
            dd($e->getMessage());
    
            return view('error');
        }
    }
    
    public function updateRole(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'telepon' => 'required|numeric',
                'role' => 'required|in:admin,ketuart,petugas', // Tambahkan validasi untuk peran
            ]);
    
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
    
            $user = User::find($id);
            $input = $request->except(['telepon', 'pass']);
            $input['tlp'] = $request->telepon;
    
            if ($request->username != $user->username) {
                $check = User::where('username', $request->username)->count();
                if ($check) {
                    return back()->with('error', "Username ($request->username) sudah ada sebelumnya.");
                }
            }
    
            if ($request->telepon != $user->tlp) {
                $checkWa = User::where('tlp', $request->telepon)->count();
                if ($checkWa) {
                    return back()->with('error', "No. Telepon ($request->telepon) sudah ada sebelumnya.");
                }
            }
    
            if ($request->pass) {
                $input['password'] = Hash::make($request->telepon);
            }
    
            // Update role pengguna
            
            $input['role'] = $request->role;
    
            $user->update($input);
    
            return back()->with('success', 'Data role berhasil diperbarui.');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }
    

   
}
