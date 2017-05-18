<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Comment extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subreddit', 'thread_id', 'comment_id', 'parent_comment_id', 'author',
        'ups', 'downs', 'score', 'body', 'body_html', 'weight'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
