<?php

namespace App\Repository;

use App\Models\Establishment;
use App\Models\Event;
use App\Models\User;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\SmartInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use stdClass;

class SmartRepository extends DBRepository implements SmartInterface
{
    private const SCOPE_ESTABLISHMENT = 'scope_search_establishment';
    private const SCOPE_PROFESSIONALS = 'scope_search_professionals';
    private const SCOPE_WEBS = 'scope_search_webs';

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

        if (in_array(self::SCOPE_PROFESSIONALS, $requestParams) || in_array(self::SCOPE_WEBS, $requestParams)) {
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
                    $query->orWhere('tb_establishments.name', 'like', "%{$data}%");
                    $query->orWhere('cnes', 'like', "%{$data}%");
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

    public function filterByScopeSearchWebs($query, $data, $field)
    {
        return $query->selectRaw("
            tb_events.name event,
            date_format(tb_events.start_at, '%d/%m/%Y %H:%i:%s') as started_at,
            GROUP_CONCAT(DISTINCT bireme_code SEPARATOR ', ') as bireme_code,
            organization,
            tb_users.name as participant,
            tb_users.cpf as cpf,
            tb_establishments.cnes as cnes,
            tb_cbos.code as cbo,
            date_format(tb_event_participants.created_at, '%d/%m/%Y %H:%i:%s') as enrolled_at,
            tb_states.acronym as state,
            tb_cities.name as city
        ")
            ->join('tb_event_descs', 'tb_event_descs.event_id', '=', 'tb_events.id')
            ->join('tb_descs', 'tb_event_descs.descs_id', '=', 'tb_descs.id')
            ->join('tb_event_participants', 'tb_event_participants.event_id', '=', 'tb_events.id')
            ->join('tb_users', 'tb_event_participants.user_id', '=', 'tb_users.id')
            ->join('tb_establishment_users', 'tb_establishment_users.user_id', 'tb_users.id')
            ->join('tb_cbos', 'tb_establishment_users.cbo_id', 'tb_cbos.id')
            ->join('tb_establishments', 'tb_establishment_users.establishment_id', 'tb_establishments.id')
            ->join('tb_cities', 'tb_cities.id', 'tb_establishments.city_id')
            ->join('tb_states', 'tb_states.id', 'tb_cities.state_id')
            ->whereNull('tb_events.deleted_at')
            ->where('primary_bond', true)
            ->whereRaw("start_at between date_format(date_sub(curdate(), interval 1 month), '%Y-%m-01') and last_day(date_sub(curdate(), interval 1 month))")
            ->where(function ($query) use ($data) {
                if ($data !== null) {
                    $query->orWhere('tb_events.name', 'like', "%{$data}%");
                    $query->orWhere('tb_users.name', 'like', "%{$data}%");
                    $query->orWhere($this->handleDate($query, $data, 'start_at'));
                    $query->orWhere($this->handleDate($query, $data, 'tb_event_participants.created_at'));
                    $query->orWhere('cpf', $data);
                    $query->orWhere('cnes', $data);
                    $query->orWhere('code', $data);
                    $query->orWhere('bireme_code', 'like', "%{$data}%");
                    $query->orWhere('tb_cities.name', 'like', "%{$data}%");
                }
            })
            ->groupBy([
                'tb_events.name',
                'tb_events.start_at',
                'organization',
                'tb_users.name',
                'tb_users.cpf',
                'tb_establishments.cnes',
                'tb_cbos.code',
                'tb_event_participants.created_at',
                'tb_states.acronym',
                'tb_cities.name'
            ]);
    }

    private function handleDate($query, $data, $field): void
    {
        $date = Carbon::parse(strtotime(str_replace('/', '-', $data)))->toDateString();

        if ($date !== '1970-01-01') {
            $query->orWhereRaw("date({$field}) =?", $date);
        }
    }
}
