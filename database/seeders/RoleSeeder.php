<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role::query()->insert([
        //     ['name' => 'petugas', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'pelanggan', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'ketuart', 'created_at' => now(), 'updated_at' => now()],
        // ]);

        $roles = Role::query()->get();

        User::query()->get()->each(function (User $user) use ($roles) {
            $user->role_id = $roles->firstWhere('name', $user->role)?->id;
            $user->save();
        });
    }
}
