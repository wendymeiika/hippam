<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleUserRequest;
use App\Http\Requests\UpdateRoleUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\JsonResponse;

class RoleUserController extends Controller
{
    /**
     * Main view tes
     */
    public function index(): View
    {
        return view('role.index', [
            'roles' => Role::query()->get(),
        ]);
    }

    /**
     * Datatable roles
     */
    public function roleList(Request $request)
    {
        // Gunakan klausa whereIn untuk mencocokkan peran yang ada dalam daftar
        $data = User::query()->with('role')->latest('created_at')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Storing data
     */
    public function store(StoreRoleUserRequest $request)
    {
        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'tlp' => $request->tlp,
            'password' => $request->tlp,
            'role_id' => $request->role_id, // Ambil nilai peran dari input form
        ]);

        return back()->with('success', 'Berhasil menambahkan pengguna dengan peran '.$request->role);
    }

    /**
     * Update
     */
    public function update(UpdateRoleUserRequest $request, User $roleUser)
    {
        $input = $request->except(['password']);

        if ($request->pass) {
            $input['password'] = $request->tlp;
        }

        $roleUser->update($input);

        return back()->with('success', 'Data role berhasil diperbarui.');
    }

    // public function destroy(User $roleUser)
    // {
    //     try {
    //         $roleUser->delete();
    //         return response()->json(['success' => 'Data role berhasil dihapus.']);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()], 500);
    //     }
    // }

    public function destroy(User $roleUser): JsonResponse
    {
        $roleUser->delete();

        return response()->json(status: 204);
    }

}
