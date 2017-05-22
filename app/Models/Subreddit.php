<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subreddit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'threads', 'weight', 'syncs'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
