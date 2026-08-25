<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    \App\Models\User::create([
        'name' => 'เจ้าหน้าที่นิติกร',
        'username' => 'admin',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
    ]);
}
}
