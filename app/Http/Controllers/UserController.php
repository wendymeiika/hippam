<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function user(): View
    {
        return view('user.list-user');
    }

    public function userList(Request $request): JsonResponse
    {
        $data = User::where('role', 'pelanggan')->orderBy('created_at', 'desc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function tambahUser(Request $request)
    {
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
            'password' => $request->tlp,
        ]);
    }

    public function updateUser(Request $request, User $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => ['required', Rule::unique(User::class, 'username')->ignore($id)],
            'alamat' => 'required',
            // 'rt' => 'required',
            // 'rw' => 'required',
            'telepon' => ['required', 'numeric', Rule::unique(User::class, 'tlp')->ignore($id)],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $input = $request->except(['telepon', 'pass']);
        $input['tlp'] = $request->telepon;

        if ($request->pass) {
            $input['password'] = $request->telepon;
        }

        $id->update($input);

        return back()->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function deleteUser(User $id): JsonResponse
    {
        $id->delete();

        return response()->json('success', 200);
    }

    public function editProfile(): View
    {
        return view('profile.edit-profile');
    }

    public function gantiPassword(): View
    {
        return view('profile.ganti-password');
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        $request->user()->update($request->validated());

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $request->user()->update([
            'password' => $request->password,
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }
}
