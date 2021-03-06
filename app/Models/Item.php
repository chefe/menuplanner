<?php

namespace App\Models;

use App\Http\Resources\ItemResource;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

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
