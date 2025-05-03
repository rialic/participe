<?php

namespace App\Repository;

use App\Models\City;
use App\Models\Event;
use App\Models\User;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\DashboardInterface;
use stdClass;

class DashboardRepository extends DBRepository implements DashboardInterface
{
    public function __construct(
        private readonly User $user,
        private readonly City $city,
        private readonly Event $event,
    )
    {
        parent::__construct();
    }

    public function model()
    {
        return stdClass::class;
    }

    public function participantsCount(): array
    {
        return $this->user->selectRaw('tb_events.organization, count(tb_users.id) as participants_count')
                        ->join('tb_event_participants', 'tb_event_participants.user_id', '=', 'tb_users.id')
                        ->join('tb_events', 'tb_event_participants.event_id', '=', 'tb_events.id')
                        ->whereRaw("tb_event_participants.created_at between DATE_FORMAT(curdate() ,'%Y-%m-01') and last_day(curdate())")
                        ->whereRaw('DATE_ADD(end_at, INTERVAL end_minutes_additions MINUTE) <= current_timestamp()')
                        ->where('type_event', 'Webaulas/palestras')
                        ->whereNull('tb_events.deleted_at')
                        ->groupBy('organization')
                        ->get()
                        ->pluck('participants_count', 'organization')
                        ->all();
    }

    public function webClassCountDone(): array
    {
        return $this->event->selectRaw('organization, count(distinct(tb_events.id)) as webclass_count')
                        ->join('tb_event_participants', 'tb_event_participants.event_id', '=', 'tb_events.id')
                        ->whereRaw("tb_events.start_at between DATE_FORMAT(curdate() ,'%Y-%m-01') and last_day(curdate())")
                        ->whereRaw('DATE_ADD(end_at, INTERVAL end_minutes_additions MINUTE) <= current_timestamp()')
                        ->where('tb_events.type_event', 'Webaulas/palestras')
                        ->whereNull('tb_events.deleted_at')
                        ->groupBy('organization')
                        ->get()
                        ->pluck('webclass_count', 'organization')
                        ->all();
    }

    public function webClassCountScheduled(): array
    {
        return $this->event->selectRaw('organization, count(tb_events.id) as webclass_count')
                        ->whereRaw("tb_events.start_at between DATE_FORMAT(curdate() ,'%Y-%m-01') and last_day(curdate())")
                        ->whereRaw('DATE_ADD(end_at, INTERVAL end_minutes_additions MINUTE) >= current_timestamp()')
                        ->where('tb_events.type_event', 'Webaulas/palestras')
                        ->whereNull('tb_events.deleted_at')
                        ->groupBy('organization')
                        ->get()
                        ->pluck('webclass_count', 'organization')
                        ->all();
    }

    public function participantsAvg(): array
    {
        return $this->event->selectRaw('tb_events.organization, round(count(tb_event_participants.event_id) / count(distinct(tb_events.id))) as participants_avg')
                        ->join('tb_event_participants', 'tb_event_participants.event_id', '=', 'tb_events.id')
                        ->whereRaw("tb_event_participants.created_at between DATE_FORMAT(curdate() ,'%Y-%m-01') and last_day(curdate())")
                        ->where('type_event', 'Webaulas/palestras')
                        ->whereNull('tb_events.deleted_at')
                        ->groupBy('organization')
                        ->get()
                        ->pluck('participants_avg', 'organization')
                        ->all();
    }

    public function participantsAvgCount(): array
    {
        return $this->event->selectRaw('round(count(tb_event_participants.event_id) / count(distinct(tb_events.id))) as participants_avg_count')
                        ->join('tb_event_participants', 'tb_event_participants.event_id', '=', 'tb_events.id')
                        ->whereRaw("tb_event_participants.created_at between DATE_FORMAT(curdate() ,'%Y-%m-01') and last_day(curdate())")
                        ->where('type_event', 'Webaulas/palestras')
                        ->whereNull('tb_events.deleted_at')
                        ->get()
                        ->pluck('participants_avg_count')
                        ->all();
    }

    public function citiesCount(): array
    {
        return $this->city->selectRaw('tb_events.organization, count(distinct(tb_cities.id)) as city_count')
                        ->join('tb_establishments', 'tb_establishments.city_id', '=', 'tb_cities.id')
                        ->join('tb_establishment_users', 'tb_establishment_users.establishment_id', '=', 'tb_establishments.id')
                        ->join('tb_event_participants', 'tb_event_participants.user_id', '=', 'tb_establishment_users.user_id')
                        ->join('tb_events', 'tb_events.id', '=', 'tb_event_participants.event_id')
                        ->whereRaw("tb_event_participants.created_at between DATE_FORMAT(current_timestamp() ,'%Y-%m-01') and curdate()")
                        ->where('primary_bond', true)
                        ->where('type_event', 'Webaulas/palestras')
                        ->whereNull('tb_events.deleted_at')
                        ->groupBy('organization')
                        ->pluck('city_count', 'organization')
                        ->all();
    }
}