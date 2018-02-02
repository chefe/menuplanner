<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MealResource extends Resource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'date' => $this->date->format('Y-m-d'),
        ]);
    }
}
