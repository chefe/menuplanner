<?php

namespace App;

use App\Http\Resources\PurchaseResource;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    protected $dates = ['time'];

    public function menuplan()
    {
        return $this->belongsTo(Menuplan::class);
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
