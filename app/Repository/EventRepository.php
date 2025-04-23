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
        return parent::query($params)->with('descs');
    }

    public function filterByScopeSearch($query, $data, $field): Builder
    {
        return $query->where(function ($query) use ($data) {
            $query->orWhere('name', 'like', "%{$data}%");
            $query->orWhere($this->handleDate($query, $data, 'start_at'));
            $query->orWhere($this->handleDate($query, $data, 'end_at'));
            $query->orWhere('organization', 'like', "%{$data}%");
            $query->orWhereHas('descs', fn($query) => $query->where('bireme_code', 'like', "%{$data}%"));
        })
        ->where('type_event', 'Webaulas/palestras');
    }

    public function filterByEventsAvailables($query, $data, $field): Builder
    {
        return $query
                    ->whereRaw("date(start_at) = curdate()")
                    ->whereRaw('DATE_ADD(end_at, INTERVAL end_minutes_additions MINUTE) >= current_timestamp()')
                    ->where('type_event', 'Webaulas/palestras');
    }

    private function handleDate($query, $data, $field): void
    {
        $date = Carbon::parse(strtotime(str_replace('/', '-', $data)))->toDateString();

        if ($date !== '1970-01-01') {
            $query->orWhereRaw("date({$field}) =?", $date);
        }
    }
}