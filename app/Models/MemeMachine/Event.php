<?php namespace App\Models\MemeMachine;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Event extends Model {

    protected static $rules = [
        'name'           => '',
        'description'    => '',
        'start_date'     => '',
        'end_date'       => '',
        'competition_id' => '',
        'type'           => '',
        'status'         => ''
    ];

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'competition_id',
        'type',
        'status'
    ];
}