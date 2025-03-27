<?php

namespace App\Repository;

use App\Models\Event;
use App\Repository\Base\DBRepository;
use App\Repository\Interfaces\EventInterface;
use Illuminate\Database\Eloquent\Builder;

class EventRepository extends DBRepository implements EventInterface
{
    public function model()
    {
        return Event::class;
    }

    public function filterByStartAt($query, $data, $field): Builder
    {
        if (optional($this->params)['end_at']) {
            return $query->where($field, '>=', "{$data} 00:00:00");
        }

        return $query->where($field, '<=', "{$data} 23:59:59");
    }

    public function filterByEndAt($query, $data, $field): Builder
    {
        return $query->where($field, '<=', "{$data} 23:59:59");
    }

    public function filterByEventsAvailables($query, $data, $field): Builder
    {
        return $query->whereRaw('DATE_ADD(end_at, INTERVAL end_minutes_additions MINUTE) >= current_timestamp()')->where('type_event', 'Webaulas/palestras');
    }
}