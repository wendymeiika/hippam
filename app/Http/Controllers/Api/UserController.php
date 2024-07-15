<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserFromPetugasRequest;

class UserController extends Controller
{
    public function index(): View
    {
        return view('user.list-user');
    }

    public function userList(Request $request): JsonResponse
    {
        // Mengambil daftar pengguna yang diinginkan
        $users = User::query()
            ->latest()
            ->whereHas('role', fn (Builder $query): Builder => $query->where('name', 'pelanggan'))
            ->get()
            ->map(function ($user, $index) {
                return [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'tlp' => $user->tlp,
                    'alamat' => $user->alamat,
                    'rt' => $user->rt,
                    'rw' => $user->rw,
                    'role_id' => $user->role_id,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'deletable' => Gate::check('delete', $user),
                    'DT_RowIndex' => $index + 1,
                ];
            });
    
        return response()->json($users);
    }
    

    public function store(StoreUserRequest $request)
    {
        Role::query()
            ->where('name', 'pelanggan')
            ->first()
            ->users()
            ->create(
                array_merge(
                    $request->validated(),
                    ['password' => Hash::make($request->tlp)]
                )
            );

        return back()->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    public function update(UpdateUserFromPetugasRequest $request, User $user)
    {
        $update = $request->validated();

        if ($request->pass) {
            $update['password'] = Hash::make($request->telepon);
        }

        $user->update($update);

        return back()->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(status: 204);
    }

    public function editProfile(): View
    {
        return view('profile.edit-profile');
    }

    public function gantiPassword(): View
    {
        return view('profile.ganti-password');
    }

    public function updateProfile(UpdateUserRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->update($request->validated());
    
            return response()->json(['message' => 'Profil berhasil diperbarui.', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperbarui profil.'], 500);
        }
    }
    

    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);
    
        return response()->json(['message' => 'Password berhasil diperbarui'], 200);
    }
    
    
}
