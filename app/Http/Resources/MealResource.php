<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MealResource extends Resource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'date' => $this->date->format('Y-m-d'),
            'start' => $this->getFormatedTime($this->start),
            'end' => $this->getFormatedTime($this->end),
        ]);
    }

    private function getFormatedTime($time)
    {
        $parts = explode(':', $time);
        return sprintf('%02d:%02d', $parts[0], $parts[1]);
    }
}
