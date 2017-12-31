<?php namespace App\Models\MemeMachine;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Competition extends Model {

    protected static $rules = [
        'name'           => '',
        'description'    => '',
        'start_date'     => '',
        'end_date'       => '',
        'winner_user_id' => ''
    ];

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'winner_user_id'
    ];
}