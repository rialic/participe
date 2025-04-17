<?php

namespace App\Repository;

use App\Models\Event;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\EventInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EventRepository extends DBRepository implements EventInterface
{
    public function model()
    {
        return Event::class;
    }

    public function query(array $params = []): Builder
    {
        $query = parent::query($params);

        return $query->with('descs');
    }

    public function filterByName($query, $data, $field): Builder
    {
        return $query->where($field, 'like', "%{$data}%");
    }

    public function filterByTypeEvent($query, $data, $field): Builder
    {
        return $query->where($field, $data);
    }

    public function filterByStartAt($query, $data, $field): Builder
    {
        $date = Carbon::parse(strtotime(str_replace('/', '-', $data)))->toDateString();

        if ($date === '1970-01-01') {
            return $query;
        }

        return $query->orWhereRaw("date({$field}) =?", $date);
    }

    public function filterByEndAt($query, $data, $field): Builder
    {
        $date = Carbon::parse(strtotime(str_replace('/', '-', $data)))->toDateString();

        if ($date === '1970-01-01') {
            return $query;
        }

        return $query->orWhereRaw("date({$field}) =?", $date);
    }

    public function filterByOrganization($query, $data, $field): Builder
    {
        return $query->orWhere($field, 'like', "%{$data}%");
    }

    public function filterByBiremeCode($query, $data, $field): Builder
    {
        return $query->orWhereHas('descs', function($query) use($data, $field) {
            $query->where($field, 'like', "%{$data}%");
        });
    }

    public function filterByEventsAvailables($query, $data, $field): Builder
    {
        return $query
                    ->whereRaw("date(start_at) = curdate()")
                    ->whereRaw('DATE_ADD(end_at, INTERVAL end_minutes_additions MINUTE) >= current_timestamp()')
                    ->where('type_event', 'Webaulas/palestras');
    }
}