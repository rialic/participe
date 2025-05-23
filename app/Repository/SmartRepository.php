<?php

namespace App\Repository;

use App\Models\Establishment;
use App\Models\Event;
use App\Models\User;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\SmartInterface;
use Illuminate\Database\Eloquent\Builder;
use stdClass;

class SmartRepository extends DBRepository implements SmartInterface
{
    private const SCOPE_ESTABLISHMENT = 'scope_search_establishment';
    private const SCOPE_PROFESSIONALS = 'scope_search_professionals';

    public function __construct(
        private readonly Establishment $establishment,
        private readonly Event $event,
        private readonly User $user,
    ) {
        parent::__construct();
    }

    protected function model()
    {
        $requestParams = array_keys(request()->all());

        if (in_array(self::SCOPE_ESTABLISHMENT, $requestParams)) {
            return Establishment::class;
        }

        if (in_array(self::SCOPE_PROFESSIONALS, $requestParams)) {
            return Event::class;
        }

        return stdClass::class;
    }

    public function filterByScopeSearchEstablishment($query, $data, $field): Builder
    {
        return $query->selectRaw('tb_establishments.name, cnes, tdiagn, teduca, tconsul')
            ->join('tb_cities', 'tb_cities.id', '=', 'tb_establishments.city_id')
            ->join('tb_states', 'tb_states.id', '=', 'tb_cities.state_id')
            ->where('acronym', 'MS')
            ->where('legal_nature', 'ADMINISTRAÇÃO PÚBLICA')
            ->where('sus', 'SIM')
            ->where('cnes', '!=', '9999999')
            ->where(function ($query) use ($data) {
                if ($data !== null) {
                    $query->where('cnes', 'like', "%{$data}%");
                }
            });
    }

    public function filterByScopeSearchProfessionals($query, $data, $field): Builder
    {
        return $query->selectRaw('distinct(tb_users.id), tb_users.name, cpf, cnes, code as cbo')
            ->join('tb_event_participants', 'tb_event_participants.event_id', '=', 'tb_events.id')
            ->join('tb_users', 'tb_event_participants.user_id', '=', 'tb_users.id')
            ->join('tb_establishment_users', 'tb_establishment_users.user_id', 'tb_users.id')
            ->join('tb_cbos', 'tb_establishment_users.cbo_id', 'tb_cbos.id')
            ->join('tb_establishments', 'tb_establishment_users.establishment_id', 'tb_establishments.id')
            ->whereNull('tb_events.deleted_at')
            ->where('primary_bond', true)
            ->whereRaw("start_at between date_format(date_sub(curdate(), interval 1 month), '%Y-%m-01') and last_day(date_sub(curdate(), interval 1 month))")
            ->union(
                $this->user->selectRaw('tb_users.id, tb_users.name, cpf, cnes, code as cbo')
                    ->join('tb_establishment_users', 'tb_establishment_users.user_id', 'tb_users.id')
                    ->join('tb_cbos', 'tb_establishment_users.cbo_id', 'tb_cbos.id')
                    ->join('tb_establishments', 'tb_establishment_users.establishment_id', 'tb_establishments.id')
                    ->where('primary_bond', true)
                    ->whereRaw("tb_users.created_at between date_format(date_sub(curdate(), interval 1 month), '%Y-%m-01') and last_day(date_sub(curdate(), interval 1 month))")
                    ->where(function ($query) use ($data) {
                        if ($data !== null) {
                            $query->orWhere('tb_users.name', 'like', "%{$data}%");
                            $query->orWhere('cpf', $data);
                            $query->orWhere('cnes', $data);
                            $query->orWhere('code', $data);
                        }
                    })
            )
            ->where(function ($query) use ($data) {
                if ($data !== null) {
                    $query->orWhere('tb_users.name', 'like', "%{$data}%");
                    $query->orWhere('cpf', $data);
                    $query->orWhere('cnes', $data);
                    $query->orWhere('code', $data);
                }
            });
    }
}
