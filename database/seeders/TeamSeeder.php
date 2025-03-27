<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = app('App\Models\Team');
        $dataCNESProxy = app('App\Proxy\DataCNES\DataCNESProxy');
        $responseDataCNES = $dataCNESProxy->fetch('team');

        collect($responseDataCNES)->each(function ($dataCNESTeam, $ine) use($team) {
            $team::firstOrCreate([
                'ine' => $ine,
                'name_team' => $dataCNESTeam['name_team'],
                'type_team' => $dataCNESTeam['type_team'],
                'active_at' => date('Y-m-d', strtotime(str_replace('/', '-', $dataCNESTeam['active_at']))),
                'deactivate_at' => !is_null($dataCNESTeam['deactivate_at']) ? date('Y-m-d', strtotime(str_replace('/', '-', $dataCNESTeam['deactivate_at']))) : null,
                'datacnes_city_id' => $dataCNESTeam['datacnes_city_id'],
                'datacnes_area_id' => $dataCNESTeam['datacnes_area_id'],
                'datacnes_team_id' => $dataCNESTeam['datacnes_team_id'],
                'establishment_id' => $dataCNESTeam['establishment_id'],
            ]);
        });
    }
}
