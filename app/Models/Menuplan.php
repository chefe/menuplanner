<?php

namespace App\Models;

use App\Http\Resources\MenuplanResource;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menuplan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['start', 'end'];

    protected $casts = [
        'start' => 'date:Y-m-d',
        'end' => 'date:Y-m-d',
    ];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function ingredients()
    {
        return $this->hasManyThrough(Ingredient::class, Meal::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'invitations');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function asResource()
    {
        return new MenuplanResource($this);
    }

    public function hasInvitationFor($email)
    {
        return $this->invitations()->where('email', $email)->count() > 0;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
