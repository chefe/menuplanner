<?php

namespace App;

use DateTimeInterface;
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

    public function getAbsolutPeopleAttribute()
    {
        return $this->people == null ? $this->menuplan->people : $this->people;
    }

    public function getDurationAttribute()
    {
        $startTimeParts = explode(':', $this->start);
        $endTimeParts = explode(':', $this->end);

        return vsprintf('%02d:%02d - %02d:%02d', [
            $startTimeParts[0],
            $startTimeParts[1],
            $endTimeParts[0],
            $endTimeParts[1],
        ]);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
