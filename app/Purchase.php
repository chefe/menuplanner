<?php

namespace App;

use App\Http\Resources\PurchaseResource;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['time'];

    public function menuplan()
    {
        return $this->belongsTo(Menuplan::class);
    }

    public function getUntilAttribute()
    {
        return self::where('menuplan_id', $this->menuplan_id)
            ->whereDate('time', '>', $this->time)
            ->orderBy('time')
            ->limit(1)
            ->value('time') ?? $this->menuplan->end->endOfDay();
    }

    public function meals()
    {
        return $this
            ->hasMany(Meal::class, 'menuplan_id', 'menuplan_id')
            ->between($this->time, $this->until);
    }

    public function ingredients()
    {
        return $this->hasManyThrough(
            Ingredient::class,
            Meal::class,
            'menuplan_id',
            'meal_id',
            'menuplan_id',
            'id'
        )->whereIn('meal_id', $this->meals()->pluck('id'));
    }

    public function asResource()
    {
        return new PurchaseResource($this);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
