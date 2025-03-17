<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $establishment = app('App\Models\Establishment');
        $dataCNESProxy = app('App\Proxy\DataCNES\DataCNESProxy');
        $responseDataCNES = $dataCNESProxy->fetch('establishments');

        collect($responseDataCNES)->each(function($dataCNESEstablishment, $dataCNESId) use($establishment) {
            $hasManagementAndLegalNature = $this->getManagement($dataCNESEstablishment['management']) && $this->getLegalNature($dataCNESEstablishment['legal_nature']);

            if (isset($dataCNESEstablishment['cnes']) && isset($dataCNESEstablishment['name']) && $hasManagementAndLegalNature) {
                $establishment::firstOrCreate([
                    'cnes' => $dataCNESEstablishment['cnes'],
                    'name' => $dataCNESEstablishment['name'],
                    'management' => $this->getManagement($dataCNESEstablishment['management']),
                    'legal_nature' => $this->getLegalNature($dataCNESEstablishment['legal_nature']),
                    'sus' => ($dataCNESEstablishment['sus'] === 'S') ? 'Sim' : 'Não',
                    'datacnes_id' => $dataCNESId,
                    'city_id' => $dataCNESEstablishment['city_id'],
                ]);
            }
        });
    }

    private function getManagement($value)
    {
        $managementList = [
            'D' => 'DUPLA',
            'E' => 'ESTADUAL',
            'M' => 'MUNICIPAL'
        ];

        return $managementList[$value] ?? null;
    }

    private function getLegalNature($value)
    {
        $legalNatureList = [
            '1' => 'ADMINISTRAÇÃO PÚBLICA',
            '2' => 'ENTIDADES EMPRESARIAIS',
            '3' => 'ENTIDADES SEM FINS LUCRATIVOS',
            '4' => 'ORGANIZAÇÕES INTERNACIONAIS/OUTRAS'
        ];

        return $legalNatureList[$value] ?? null;
    }
}
