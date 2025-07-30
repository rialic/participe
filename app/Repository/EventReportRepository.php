<?php

namespace App\Repository;

use App\Models\Event;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\EventReportInterface;
use Illuminate\Database\Eloquent\Builder;

class EventReportRepository extends DBRepository implements EventReportInterface
{
    public function model()
    {
        return Event::class;
    }

    public function query(array $params = []): Builder
    {
        $query = parent::query($params);

        return $query->selectRaw("
            CONCAT(tb_events.uuid, tb_event_participants.id) as uuid,
            tb_events.name,
            tb_events.organization,
            tb_events.start_at,
            tb_events.end_at,
            GROUP_CONCAT(distinct(tb_descs.bireme_code) SEPARATOR ' / ') as descs,
            tb_users.name as participant,
            date_format(tb_event_participants.created_at, '%d/%m/%Y %H:%i:%s') as signed_up_at,
            date_format(tb_event_participants.rated_at, '%d/%m/%Y %H:%i:%s') as rated_at,
            rating_event,
            rating_event_schedule,
            hint,
            tb_cbos.name as cbo,
            tb_states.acronym as state,
            tb_cities.name as city,
            tb_macro_zones.name as macro_zone,
            tb_micro_zones.name as micro_zone
        ")
        ->join('tb_event_participants', 'tb_event_participants.event_id', '=', 'tb_events.id')
        ->join('tb_users', 'tb_event_participants.user_id', '=', 'tb_users.id')
        ->join('tb_event_descs', 'tb_event_descs.event_id', '=', 'tb_events.id')
        ->join('tb_descs', 'tb_event_descs.descs_id', '=', 'tb_descs.id')
        ->join('tb_establishment_users', 'tb_establishment_users.user_id', '=', 'tb_users.id')
        ->join('tb_cbos', 'tb_establishment_users.cbo_id', '=', 'tb_cbos.id')
        ->join('tb_establishments', 'tb_establishment_users.establishment_id', '=', 'tb_establishments.id')
        ->join('tb_cities', 'tb_establishments.city_id', '=', 'tb_cities.id')
        ->leftJoin('tb_micro_zones', 'tb_cities.micro_zone_id', '=', 'tb_micro_zones.id')
        ->leftJoin('tb_macro_zones', 'tb_micro_zones.macro_zone_id', '=', 'tb_macro_zones.id')
        ->join('tb_states', 'tb_cities.state_id', '=', 'tb_states.id')
        ->where('primary_bond', '=', true)
        ->whereNull('tb_events.deleted_at')
        ->where('type_event', 'Webaulas/palestras')
        ->groupBy([
            'uuid',
            'tb_events.name',
            'tb_events.organization',
            'tb_events.start_at',
            'tb_events.end_at',
            'tb_users.name',
            'tb_event_participants.created_at',
            'tb_event_participants.rated_at',
            'rating_event',
            'rating_event_schedule',
            'hint',
            'tb_cbos.name',
            'tb_states.acronym',
            'tb_cities.name',
            'tb_macro_zones.name',
            'tb_micro_zones.name',
        ]);
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where('tb_events.name', 'like', "%{$data}%");
    }

    public function filterByStartAtBegin($query, $data, $field): Builder|Event
    {
        if(!empty($this->params['start_at_end'])) {
            return $query;
        }

        return $query->where('start_at', '>=', $data);
    }

    public function filterByStartAtEnd($query, $data, $field): Builder|Event
    {
        if(empty($this->params['start_at_begin'])) {
            return $query;
        }

        return $query->whereRaw('date(start_at) between ? and ?', [$this->params['start_at_begin'], $data]);
    }

    public function filterByParticipant($query, $data, $field): Builder
    {
        return $query->where('tb_users.name', 'like', "%{$data}%");
    }

    public function filterByOrganization($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }

    public function filterByCbo($query, $data, $field): Builder
    {
        return $query->where('tb_cbos.uuid', $data);
    }

    public function filterByDescBireme($query, $data, $field): Builder
    {
        return $query->where('tb_descs.bireme_code', 'like', "%{$data}%");
    }

    public function filterByState($query, $data, $field): Builder
    {
        return $query->where('tb_states.uuid', $data);
    }

    public function filterByCity($query, $data, $field): Builder
    {
        return $query->where('tb_cities.uuid', $data);
    }

    public function filterByMacroZone($query, $data, $field): Builder
    {
        return $query->where('tb_macro_zones.uuid', $data);
    }

    public function filterByMicroZone($query, $data, $field): Builder
    {
        return $query->where('tb_micro_zones.uuid', $data);
    }
}