<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\MealResource;

class Meal extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

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
        return new MealResource($this);
    }
}
