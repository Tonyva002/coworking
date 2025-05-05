<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@cowork.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Crear usuario cliente
        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@cowork.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
