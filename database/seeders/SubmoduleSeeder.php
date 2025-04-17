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
                Submodule::firstOrCreate(['name' => 'Webaulas', 'module_id' => $module->id, 'order' => 1]);
                Submodule::firstOrCreate(['name' => 'Cursos', 'module_id' => $module->id, 'order' => 2]);
            }

            if ($module->name === 'Configurações') {
                Submodule::firstOrCreate(['name' => 'Usuários', 'module_id' => $module->id, 'order' => 1]);
                Submodule::firstOrCreate(['name' => 'Papéis', 'module_id' => $module->id, 'order' => 2]);
            }
        });
    }
}
