<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = collect([
            1 => collect([
                (object) ['name' => 'Tambah Pembayaran', 'roles' => [2, 4]],
                (object) ['name' => 'Validasi', 'roles' => [1]],
                (object) ['name' => 'Baca Seluruh Riwayat', 'roles' => [1, 3, 4]],
            ]),
            2 => collect([
                (object) ['name' => 'Tambah Pengumuman', 'roles' => [1]],
                (object) ['name' => 'Perbarui Pengumuman', 'roles' => [1]],
                (object) ['name' => 'Hapus Pengumuman', 'roles' => [1]],
            ]),
            3 => collect([
                (object) ['name' => 'Tambah Keluhan', 'roles' => [2, 4]],
                (object) ['name' => 'Balas Keluhan', 'roles' => [1, 2, 4]],
            ]),
            4 => collect([
                (object) ['name' => 'Atur izin akses', 'roles' => [3]],
            ]),
            5 => collect([
                (object) ['name' => 'Tambah Pengaduan', 'roles' => [2, 4]],
            ]),
        ]);

        Group::query()->get()->each(function (Group $group) use ($permissions) {
            // Tambah permission baru
            $group->permissions()->createMany(
                $permissions->get($group->id)
                    ->map(fn ($collect) => ['name' => $collect->name])
                    ->toArray()
            )->each(function (Permission $permission) use ($permissions) {
                $permission->roles()->sync(
                    $permissions->get($permission->group_id)->firstWhere('name', $permission->name)?->roles
                );
            });
        });
    }
}
