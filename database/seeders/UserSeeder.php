<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'nim' => null,
                'password' => Hash::make('123'),
                'role' => 'admin'
            ],
            [
                'name' => 'operator',
                'email' => 'operator@gmail.com',
                'nim' => null,
                'password' => Hash::make('123'),
                'role' => 'operator'
            ],
            [
                'name' => 'Pengawas',
                'email' => 'pengawas@gmail.com',
                'nim' => null,
                'password' => Hash::make('123'),
                'role' => 'pengawas'
            ],
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'nim' => '12345678',
                'password' => Hash::make('123'),
                'role' => 'user'
            ],
        ]);
    }
}
