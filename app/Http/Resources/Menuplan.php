<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Menuplan extends Resource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'start' => $this->start->format('Y-m-d'),
            'end' => $this->end->format('Y-m-d'),
        ]);
    }
}
