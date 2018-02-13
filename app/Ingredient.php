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
    
    public function getQuantityForShoppingListAttribute()
    {
        $people = ($this->meal->people == null ? $this->meal->menuplan->people : $this->meal->people);
        return $this->quantity * ($people / $this->meal->ingredients_for);
    }
}
