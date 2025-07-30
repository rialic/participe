<?php

namespace App\Repository\Interfaces;

use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;

interface EventReportInterface
{
    public function filterByName($query, $data, $field): Builder;
    public function filterByStartAtBegin($query, $data, $field): Builder|Event;
    public function filterByStartAtEnd($query, $data, $field): Builder|Event;
    public function filterByParticipant($query, $data, $field): Builder;
    public function filterByOrganization($query, $data, $field): Builder;
    public function filterByCbo($query, $data, $field): Builder;
    public function filterByDescBireme($query, $data, $field): Builder;
    public function filterByState($query, $data, $field): Builder;
    public function filterByCity($query, $data, $field): Builder;
    public function filterByMacroZone($query, $data, $field): Builder;
    public function filterByMicroZone($query, $data, $field): Builder;
}