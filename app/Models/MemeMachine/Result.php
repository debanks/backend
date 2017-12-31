<?php namespace App\Models\MemeMachine;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Result extends Model {

    protected static $rules = [
        'competition_id' => '',
        'event_id'       => '',
        'score'          => '',
        'user_id'        => '',
        'review'         => ''
    ];

    protected $fillable = [
        'competition_id',
        'event_id',
        'score',
        'user_id',
        'review'
    ];
}