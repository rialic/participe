<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $adminRole = Role::where('name', 'ADMIN')->first();
        $adjutantRole = Role::where('name', 'ADJUTOR')->first();
        $password = Hash::make('Password#1');

        $user = $user::firstOrCreate([
            'name' => 'Rhiali Cândido',
            'email' => 'rhiali_cs@hotmail.com',
            'sex' => 'Masculino',
            'cpf' => '010.528.351-71',
            'type_professional' => '04 Outros'
        ],
        [
            'password' => $password
        ]);
        $user->roles()->sync([$adminRole->id]);

        $user = $user::firstOrCreate([
            'name' => 'Euder Alexandre Nunes',
            'email' => 'enunes@saude.ms.gov.br',
            'sex' => 'Masculino',
            'cpf' => '835.427.011-49',
            'type_professional' => '01 Profissional de saúde'
        ],
        [
            'password' => $password
        ]);
        $user->roles()->sync([$adminRole->id]);

        $user = $user::firstOrCreate([
            'name' => 'Carlos Eduardo de Freitas',
            'email' => 'cadufreitasti@gmail.com',
            'sex' => 'Masculino',
            'cpf' => '004.242.469-02',
            'type_professional' => '01 Profissional de saúde'
        ],
        [
            'password' => $password
        ]);
        $user->roles()->sync([$adminRole->id]);

        $user = $user::firstOrCreate([
            'name' => 'Gabriel Takeda Fernandes da Silva',
            'email' => 'gabrieltakedaaa@gmail.com',
            'sex' => 'Masculino',
            'cpf' => '063.019.511-07',
            'type_professional' => '04 Outros'
        ],
        [
            'password' => $password
        ]);
        $user->roles()->sync([$adjutantRole->id]);

        $user = $user::firstOrCreate([
            'name' => 'Juno Vinicios Terto',
            'email' => 'juno.terto@gmail.com',
            'sex' => 'Masculino',
            'cpf' => '049.896.531-73',
            'type_professional' => '04 Outros'
        ],
        [
            'password' => $password
        ]);
        $user->roles()->sync([$adjutantRole->id]);

        $user = $user::firstOrCreate([
            'name' => 'Josué Nunes Yahn Junior',
            'email' => 'josue.nyahn@gmail.com',
            'sex' => 'Masculino',
            'cpf' => '018.725.941-07',
            'type_professional' => '04 Outros'
        ],
        [
            'password' => $password
        ]);
        $user->roles()->sync([$adjutantRole->id]);

        $user = $user::firstOrCreate([
            'name' => 'Beatriz Thaynara Caetano da Silva',
            'email' => 'beatrizthaynara21@outlook.com',
            'sex' => 'Feminino',
            'cpf' => '063.603.721-50',
            'type_professional' => '01 Profissional de saúde'
        ],
        [
            'password' => $password
        ]);
        $user->roles()->sync([$adjutantRole->id]);

        $user = $user::firstOrCreate([
            'name' => 'Débora Cardoso Ajala',
            'email' => 'debora.ajala@saude.ms.gov.br',
            'sex' => 'Feminino',
            'cpf' => '058.440.891-97',
            'type_professional' => '04 Outros'
        ],
        [
            'password' => $password
        ]);
        $user->roles()->sync([$adjutantRole->id]);
    }
}
