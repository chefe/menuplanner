<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function menuplan()
    {
        return $this->belongsTo(Menuplan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accept()
    {
        $this->user_id = auth()->id();
        $this->save();
    }

    public function canBeAcceptedBy($user)
    {
        return $user->email == $this->email && $this->user_id == null;
    }

    public function canBeDeclinedBy($user)
    {
        return $user->email == $this->email || $this->user_id == $user->id;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
