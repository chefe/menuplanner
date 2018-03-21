<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
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
}
