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
        Module::firstOrCreate(['name' => 'Dashboard', 'order' => 1]);
        Module::firstOrCreate(['name' => 'Tele-educação', 'order' => 2]);
        Module::firstOrCreate(['name' => 'Smart', 'order' => 3]);
        Module::firstOrCreate(['name' => 'Configurações', 'order' => 4]);
    }
}
