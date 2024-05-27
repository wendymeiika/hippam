<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::query()->insert([
            ['name' => 'Pembayaran', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pengumuman', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keluhan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Administrasi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pengaduan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
