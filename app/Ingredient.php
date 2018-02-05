<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $guarded = [];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
