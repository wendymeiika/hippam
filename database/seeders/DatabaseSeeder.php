<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['id' => 1, 'name' => 'petugas'],
            ['id' => 2, 'name' => 'pelanggan'],
            ['id' => 3, 'name' => 'admin'],
            ['id' => 4, 'name' => 'ketuart']
        ]);
        
        User::create([
            'nama' => 'Mirta',
            'username' => 'mirta',
            'password' => '088235837600',
            'role_id' => 1,
            'tlp' => '088235837600',
            'alamat' => 'Wadung',
            'rt' => 'Wadung',
            'rw' => 'Wadung',
        ]);   
    }
}
