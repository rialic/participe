<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city = app('App\Models\City');
        $state = app('App\Models\State');
        $macroZone = app('App\Models\MacroZone');
        $microZone = app('App\Models\MicroZone');
        $dataCNESProxy = app('App\Proxy\DataCNES\DataCNESProxy');
        $responseDataCNES = $dataCNESProxy->fetch('cities');

        collect($responseDataCNES)->each(function ($dataCNESCity, $acronym) use ($state, $city) {
            $state = $state::where('acronym', $acronym)->first();

            collect($dataCNESCity)->each(function ($dataCNESCity, $dataCNESId) use ($state, $city) {
                $city::firstOrCreate([
                    'name' => $dataCNESCity,
                    'state_id' => $state->id,
                    'datacnes_id' => $dataCNESId
                ]);
            });
        });

        $this->fillMacroMicroRegions($macroZone, $microZone, $state, $city);
    }

    private function fillMacroMicroRegions($macroZone, $microZone, $state, $city)
    {
        $mmList = [
            'CENTRO' => [
                'REGIÃO BAIXO PANTANAL' => [
                    'ANASTÁCIO', 'AQUIDAUANA', 'BELA VISTA', 'BODOQUENA', 'BONITO', 'CARACOL',
                    'DOIS IRMÃOS DO BURITI', 'GUIA LOPES DA LAGUNA', 'JARDIM', 'MARACAJU',
                    'NIOAQUE', 'PORTO MURTINHO'
                ],
                'REGIÃO CENTRO' => [
                    'BANDEIRANTES', 'CAMAPUÃ', 'CAMPO GRANDE', 'CORGUINHO', 'JARAGUARI',
                    'RIBAS DO RIO PARDO', 'ROCHEDO', 'SIDROLANDIA', 'TERENOS'
                ],
                'REGIÃO NORTE' => [
                    'ALCINOPOLIS', 'COXIM', 'PEDRO GOMES', 'SONORA', 'FIGUEIRÃO', 'RIO NEGRO',
                    'RIO VERDE DE MT', 'SÃO GABRIEL DO OESTE'
                ],
            ],
            'PANTANAL' => [
                'REGIÃO PANTANAL' => ['CORUMBÁ', 'LADÁRIO', 'MIRANDA']
            ],
            'CONE SUL' => [
                'REGIÃO CENTRO SUL' => [
                    'CAARAPÓ', 'DEODÁPOLIS', 'DOURADINA', 'DOURADOS', 'FATIMA DO SUL',
                    'GLÓRIA DE DOURADOS', 'ITAPORÃ', 'JATEÍ', 'LAGUNA CARAPÃ', 'NOVA ALVORADA DO SUL',
                    'RIO BRILHANTE', 'VICENTINA'
                ],
                'REGIÃO SUDESTE' => [
                    'ANAURILANDIA', 'ANGÉLICA', 'BATAIPORÃ', 'IVINHEMA', 'NOVA ANDRADINA',
                    'NOVO HORIZONTE DO SUL', 'TAQUARUSSU'
                ],
                'REGIÃO SUL FRONTEIRA' => [
                    'AMAMBAI', 'ANTONIO JOÃO', 'ARAL MOREIRA', 'CORONEL SAPUCAIA', 'ELDORADO',
                    'IGUATEMI', 'ITAQUIRAI', 'JAPORÃ', 'JUTI', 'MUNDO NOVO', 'NAVIRAÍ', 'PARANHOS',
                    'PONTA PORÃ', 'SETE QUEDAS', 'TACURU'
                ],
                'REGIÃO NORDESTE' => [
                    'APARECIDA DO TABOADO', 'CASSILÂNDIA', 'CHAPADÃO DO SUL', 'COSTA RICA',
                    'INOCÊNCIA', 'PARAÍSO DAS ÁGUAS', 'PARANAÍBA'
                ],
                'REGIÃO LESTE' => [
                    'ÁGUA CLARA', 'BATAGUASSU', 'BRASILÂNDIA', 'SANTA RITA DO PARDO', 'SELVÍRIA',
                    'TRÊS LAGOAS'
                ],
            ]
        ];

        $state = $state::where('acronym', 'MS')->first();

        foreach ($mmList as $macro => $microList) {
            $macroZone = $macroZone::firstOrCreate(['name' => $macro]);

            foreach($microList as $micro => $cityList) {
                $microZone = $microZone::firstOrCreate(['name' => $micro, 'macro_zone_id' => $macroZone->id]);

                $city::whereIn('name', $cityList)->where('state_id', $state->id)->update(['micro_zone_id' => $microZone->id]);
            }
        }
    }
}
