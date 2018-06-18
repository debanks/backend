<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Match extends Model {

    protected static $rules = [
        'name'          => '',
        'type'          => '',
        'plane_start_x' => '',
        'plane_start_y' => '',
        'plane_end_x'   => '',
        'plane_end_y'   => '',
        'drop_x'        => '',
        'drop_y'        => '',
        'kills'         => '',
        'place'         => '',
        'died'          => '',
        'end_x'         => '',
        'end_y'         => ''
    ];

    protected $fillable = [
        'name',
        'type',
        'drop_x',
        'drop_y',
        'kills',
        'plane_start_x',
        'plane_start_y',
        'plane_end_x',
        'plane_end_y',
        'place',
        'died',
        'end_x',
        'end_y'
    ];
}