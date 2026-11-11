<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin LPPM
        User::create([
            'name' => 'Admin LPPM',
            'email' => 'admin@mahardika.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Dosen
        User::create([
            'name' => 'Dosen Pengusul, M.T.',
            'email' => 'dosen@mahardika.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
        ]);

        // 3. Akun Reviewer
        User::create([
            'name' => 'Reviewer LPPM, Dr.',
            'email' => 'reviewer@mahardika.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'reviewer',
        ]);
    }
}

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
