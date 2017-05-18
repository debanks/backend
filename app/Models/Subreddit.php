<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Subreddit extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'threads', 'weight',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
