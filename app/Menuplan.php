<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\MenuplanResource;

class Menuplan extends Model
{
    protected $guarded = [];

    protected $dates = ['start', 'end'];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function asResource()
    {
        return new MenuplanResource($this);
    }
}
