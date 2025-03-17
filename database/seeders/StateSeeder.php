<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $state = app('App\Models\State');

        $state::firstOrCreate([
            'name' => 'ACRE',
            'acronym' => 'AC'
        ]);

        $state::firstOrCreate([
            'name' => 'ALAGOAS',
            'acronym' => 'AL'
        ]);

        $state::firstOrCreate([
            'name' => 'AMAZONAS',
            'acronym' => 'AM'
        ]);

        $state::firstOrCreate([
            'name' => 'AMAPA',
            'acronym' => 'AP'
        ]);

        $state::firstOrCreate([
            'name' => 'BAHIA',
            'acronym' => 'BA'
        ]);

        $state::firstOrCreate([
            'name' => 'CEARA',
            'acronym' => 'CE'
        ]);

        $state::firstOrCreate([
            'name' => 'DISTRITO FEDERAL',
            'acronym' => 'DF'
        ]);

        $state::firstOrCreate([
            'name' => 'ESPIRITO SANTO',
            'acronym' => 'ES'
        ]);

        $state::firstOrCreate([
            'name' => 'GOIAS',
            'acronym' => 'GO'
        ]);

        $state::firstOrCreate([
            'name' => 'MARANHAO',
            'acronym' => 'MA'
        ]);

        $state::firstOrCreate([
            'name' => 'MINAS GERAIS',
            'acronym' => 'MG'
        ]);

        $state::firstOrCreate([
            'name' => 'MATO GROSSO DO SUL',
            'acronym' => 'MS'
        ]);

        $state::firstOrCreate([
            'name' => 'MATO GROSSO',
            'acronym' => 'MT'
        ]);

        $state::firstOrCreate([
            'name' => 'PARA',
            'acronym' => 'PA'
        ]);

        $state::firstOrCreate([
            'name' => 'PARAIBA',
            'acronym' => 'PB'
        ]);

        $state::firstOrCreate([
            'name' => 'PERNAMBUCO',
            'acronym' => 'PE'
        ]);

        $state::firstOrCreate([
            'name' => 'PIAUI',
            'acronym' => 'PI'
        ]);

        $state::firstOrCreate([
            'name' => 'PARANA',
            'acronym' => 'PR'
        ]);

        $state::firstOrCreate([
            'name' => 'RIO DE JANEIRO',
            'acronym' => 'RJ'
        ]);

        $state::firstOrCreate([
            'name' => 'RIO GRANDE DO NORTE',
            'acronym' => 'RN'
        ]);

        $state::firstOrCreate([
            'name' => 'RONDONIA',
            'acronym' => 'RO'
        ]);

        $state::firstOrCreate([
            'name' => 'RORAIMA',
            'acronym' => 'RR'
        ]);

        $state::firstOrCreate([
            'name' => 'RIO GRANDE DO SUL',
            'acronym' => 'RS'
        ]);

        $state::firstOrCreate([
            'name' => 'SANTA CATARINA',
            'acronym' => 'SC'
        ]);

        $state::firstOrCreate([
            'name' => 'SERGIPE',
            'acronym' => 'SE'
        ]);

        $state::firstOrCreate([
            'name' => 'SAO PAULO',
            'acronym' => 'SP'
        ]);

        $state::firstOrCreate([
            'name' => 'TOCANTINS',
            'acronym' => 'TO'
        ]);
    }
}
