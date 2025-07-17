<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'userId' => 'MHS001',
                'password' => Hash::make('password'),
                'roleName' => 'mahasiswa',
                'statusLogin' => 'offline'
            ],
            [
                'userId' => 'DSN001',
                'password' => Hash::make('password'),
                'roleName' => 'dosen',
                'statusLogin' => 'offline'
            ],
            [
                'userId' => 'ADM001',
                'password' => Hash::make('password'),
                'roleName' => 'admin',
                'statusLogin' => 'offline'
            ]
        ]);
    }
}
