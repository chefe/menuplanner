<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'can_be_deleted' => $this->ingredients()->count() == 0,
        ]);
    }
}
