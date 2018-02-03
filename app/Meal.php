<?php

namespace App;

use App\Http\Resources\MealResource;
use Illuminate\Database\Eloquent\Model;

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
