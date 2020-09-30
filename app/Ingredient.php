<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

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
        return $this->quantity * ($this->meal->absolut_people / $this->meal->ingredients_for);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
