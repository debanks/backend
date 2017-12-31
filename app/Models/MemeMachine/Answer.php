<?php namespace App\Models\MemeMachine;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Answer extends Model {

    protected static $rules = [
        'poll_id'   => '',
        'choice_id' => '',
        'user_id'   => ''
    ];

    protected $fillable = [
        'poll_id',
        'choice_id',
        'user_id'
    ];
}