<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'id' => (string) Str::uuid(),
            'fullname' => 'test developer',
            'username' => 'developer',
            'email' => 'usertestdeveloper330@gmail.com',
            'password' => bcrypt('$2y$10$FE/pnHkVigttsXQpoFiZ6uAx2GKODjW6oW5uhmv5Q09PYTrAVdkYm'), // Pastikan untuk mengenkripsi password
            'lokasi' => 'Bandung',
            'phone' => '081234567812',
            'role' =>'super_admin',
        ]);
    }
}
