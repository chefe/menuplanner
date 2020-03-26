<?php

namespace App;

use DateTimeInterface;
use App\Http\Resources\ItemResource;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];

    public function menuplan()
    {
        return $this->belongsTo(Menuplan::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function asResource()
    {
        return new ItemResource($this);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
