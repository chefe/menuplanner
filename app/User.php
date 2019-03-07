<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function menuplans()
    {
        return $this->hasMany(Menuplan::class);
    }

    public function sharedMenuplans()
    {
        return $this->belongsToMany(Menuplan::class, 'invitations');
    }

    public function getInvitations()
    {
        return Invitation::with('menuplan')
            ->where('email', $this->email)
            ->orWhere('user_id', $this->id)
            ->get();
    }
}
