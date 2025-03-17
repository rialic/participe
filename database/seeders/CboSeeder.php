<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cbo = app('App\Models\Cbo');
        $dataCNESProxy = app('App\Proxy\DataCNES\DataCNESProxy');
        $responseDataCNES = $dataCNESProxy->fetch('cbo');

        collect($responseDataCNES)->each(function($dataCNESCBO, $cboCode) use ($cbo){
            $cbo::firstOrCreate([
                'code' => $cboCode,
                'name' => $dataCNESCBO['name']
            ]);
        });
    }
}
