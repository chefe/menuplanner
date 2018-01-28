<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menuplan extends Model
{
    /** */
    protected $guarded = [];

    /** */
    protected $dates = ['start', 'end'];
}
