<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run()
    {
        DB::table('dosens')->insert([
            'userId' => 'DSN001',
            'nidn' => 'DSN001',
            'nama' => 'Dina Dosen',
            'email' => 'dina@dosen.com',
            'status' => 'aktif'
        ]);
    }
}
