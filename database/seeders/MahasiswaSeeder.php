<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        DB::table('mahasiswas')->insert([
            'userId' => 'MHS001',
            'nim' => 'MHS001',
            'nama' => 'Ali Mahasiswa',
            'email' => 'ali@student.com',
            'thnAngkatan' => '2022',
            'status' => 'aktif'
        ]);
    }
}
