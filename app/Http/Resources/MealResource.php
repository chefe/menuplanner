<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'date' => $this->date->format('Y-m-d'),
            'start' => $this->getFormatedTime($this->start),
            'end' => $this->getFormatedTime($this->end),
            'menuplan' => new MenuplanResource($this->menuplan),
        ]);
    }

    private function getFormatedTime($time)
    {
        $parts = explode(':', $time);

        return sprintf('%02d:%02d', $parts[0], $parts[1]);
    }
}
