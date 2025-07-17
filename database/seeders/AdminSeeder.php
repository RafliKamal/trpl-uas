<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->insert([
            'userId' => 'ADM001',
            'nama' => 'Arif Admin',
            'email' => 'admin@kampus.com',
            'divisi' => 'a'
        ]);
    }
}
