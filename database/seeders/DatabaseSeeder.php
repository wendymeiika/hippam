<?php

namespace Database\Seeders;

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
        User::create([
            'nama' => 'Mirta',
            'username' => 'mirta',
            'password' => 'password',
            'role' => 'petugas',
            'tlp' => '088235837600',
            'alamat' => 'Wadung',
        ]);

        User::create([
            'nama' => 'Ali',
            'username' => 'ali',
            'password' => 'password',
            'role' => 'pelanggan',
            'tlp' => '087678096123',
            'alamat' => 'Wadung Kaligondo',
        ]);
    }
}
