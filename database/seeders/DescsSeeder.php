<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DescsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $descs = app('App\Models\Descs');
        $dataCNESProxy = app('App\Proxy\DataCNES\DataCNESProxy');
        $responseDataCNES = $dataCNESProxy->fetch('descs');

        collect($responseDataCNES)->each(function($descBireme, $descCode) use ($descs){
            $descs::firstOrCreate([
                'bireme_code' => $descCode,
                'name' => $descBireme['name'] ?? null,
                'description' => $descBireme['description'] ?? null
            ]);
        });
    }
}
