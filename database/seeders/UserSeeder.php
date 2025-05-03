<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = app('App\Models\User');

        $user::firstOrCreate([
            'name' => 'Rhiali Cândido',
            'email' => 'rhiali_cs@hotmail.com',
            'sex' => 'Masculino',
            'cpf' => '01052835171',
            'password' => Hash::make('password'),
            'type_professional' => '01 Profissional de saúde'
        ]);
    }
}
