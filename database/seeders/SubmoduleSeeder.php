<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Submodule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubmoduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moduleList = Module::get();

        $moduleList->each(function($module, $key) {
            if ($module->name === 'Tele-educação') {
                Submodule::firstOrCreate(['name' => 'Web-aulas', 'module_id' => $module->id]);
                Submodule::firstOrCreate(['name' => 'Cursos', 'module_id' => $module->id]);
            }

            if ($module->name === 'Configurações') {
                Submodule::firstOrCreate(['name' => 'Smart', 'module_id' => $module->id]);
                Submodule::firstOrCreate(['name' => 'Usuários', 'module_id' => $module->id]);
                Submodule::firstOrCreate(['name' => 'Ocupações', 'module_id' => $module->id]);
                Submodule::firstOrCreate(['name' => 'Estabelecimentos', 'module_id' => $module->id]);
                Submodule::firstOrCreate(['name' => 'Papéis', 'module_id' => $module->id]);
                Submodule::firstOrCreate(['name' => 'Permissões', 'module_id' => $module->id]);
            }
        });
    }
}
