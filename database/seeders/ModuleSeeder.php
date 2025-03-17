<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::firstOrCreate(['name' => 'Dashboard']);
        Module::firstOrCreate(['name' => 'Tele-educação']);
        Module::firstOrCreate(['name' => 'Configurações']);
    }
}
