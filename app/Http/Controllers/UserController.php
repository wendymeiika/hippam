<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserFromPetugasRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(): View
    {
        return view('user.list-user');
    }

    public function userList(Request $request): JsonResponse
    {
        return DataTables::of(
            User::query()
                ->latest()
                ->whereHas('role', fn (Builder $query): Builder => $query->where('name', 'pelanggan'))
                ->get()
        )->addIndexColumn()
            ->addColumn('deletable', fn (User $user) => Gate::check('delete', $user))
            ->make(true);
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
                    ['password' => $request->tlp]
                )
            );

        return back()->with('success', 'Data Pelanggan berhasil ditambahkan.');

    }

    public function update(UpdateUserFromPetugasRequest $request, User $user)
    {
        $update = $request->validated();

        if ($request->pass) {
            $update['password'] = $request->telepon;
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
