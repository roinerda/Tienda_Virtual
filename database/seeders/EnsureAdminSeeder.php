<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EnsureAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tuapp.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'), 
                'is_admin' => 1
            ]
        );
    }
}