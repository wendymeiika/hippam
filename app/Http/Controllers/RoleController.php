<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Group;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        return view('role.permission', [
            'groups' => Group::query()->with('permissions')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::query()->create(['name' => $request->name]);

        $role->permissions()->sync($request->permissions);

        return redirect()->route('role.index')->with('success', __('Role telah ditambahkannn'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update(['name' => $request->name]);

        $role->permissions()->sync($request->permissions);

        return redirect()->route('role.index')->with('success', __('Role telah diperbarui'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role): JsonResponse
    {
        Gate::authorize('delete', $role);

        $role->delete();

        return response()->json(status: 204);
        // return redirect()->route('role.index')->with('success', __('Role telah diperbarui'));
    }

    /**
     * Datatable roles
     */
    public function datatable()
    {
        return DataTables::of(
            Role::query()->with('permissions')->get()
        )->addIndexColumn()
            ->addColumn('deletable', fn (Role $role) => Gate::check('delete', $role))
            ->make(true);
    }
}
