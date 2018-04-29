<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\PurchaseResource;

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
}
