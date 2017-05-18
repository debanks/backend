<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Thread extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subreddit', 'thread_id', 'title', 'author', 'comments',
        'ups', 'downs', 'score', 'url', 'spoiler', 'over18', 'weight'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
