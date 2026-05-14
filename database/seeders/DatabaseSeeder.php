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
        User::query()->updateOrCreate([
            'mail' => 'admin@nomaenerji.local',
        ], [
            'ad' => 'Admin',
            'soyad' => 'User',
            'telefon' => null,
            'role' => 'admin',
            'aktif' => true,
            'sifre' => 'admin1234',
        ]);
    }
}
