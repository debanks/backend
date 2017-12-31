<?php namespace App\Models\MemeMachine;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Stage extends Model {

    protected static $rules = [
        'name'        => '',
        'description' => '',
        'start_date'  => '',
        'end_date'    => '',
        'type'        => '',
        'status'      => '',
        'poll_id'     => '',
        'event_id'    => '',
        'stage_id'    => ''
    ];

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'type',
        'status',
        'poll_id',
        'event_id',
        'stage_id'
    ];
}