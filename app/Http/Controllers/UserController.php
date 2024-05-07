<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use DataTables;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function user()
    {
        try {
            return view('user.list-user');
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function userList(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'pelanggan')->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function tambahUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required|unique:user',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'tlp' => 'required|numeric|unique:user',
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
                'role' => 'pelanggan',
            ]);

            return back()->with('success', 'Berhasil menambahkan Pelanggan');
        } catch (Exception $e) {
            dd($e->getMessage());

            return view('error');
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'telepon' => 'required|numeric',
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

            $user->update($input);

            return back()->with('success', 'Data Pelanggan berhasil diperbarui.');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        try {
            User::destroy($id);

            return response()->json('success', 200);
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function editProfile()
    {
        try {
            return view('profile.edit-profile');
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function gantiPassword()
    {
        try {
            return view('profile.ganti-password');
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'telepon' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $user = User::find(Auth::user()->id);
            $input = $request->except(['telepon']);
            $input['tlp'] = $request->telepon;

            if ($request->username != $user->username) {
                $check = User::where('username', $request->username)->count();
                if ($check) {
                    return back()->with('error', "Username ($request->username) sudah ada sebelumnya.");
                }
            }

            if ($request->tlp != $user->tlp) {
                $check = User::where('tlp', $request->tlp)->count();
                if ($check) {
                    return back()->with('error', "No. Telepon ($request->telepon) ini sudah ada sebelumnya.");
                }
            }

            $user->update($input);

            return back()->with('success', 'Profile berhasil diperbarui.');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $user = User::find(Auth::user()->id);

            if (Hash::check($request->old_password, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);

                return back()->with('success', 'Password berhasil diperbarui');
            }

            return back()->with('error', 'Password lama tidak sesuai');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }
}
