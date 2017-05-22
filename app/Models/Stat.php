<?php
/**
 * Created by PhpStorm.
 * User: delta
 * Date: 5/21/2017
 * Time: 10:39 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Stat extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subreddit', 'thread_id', 'comment_id',
        'ups', 'downs', 'score', 'comments'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}