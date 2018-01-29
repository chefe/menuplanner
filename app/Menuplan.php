<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menuplan extends Model
{
    protected $guarded = [];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
