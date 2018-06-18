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
        'type'  => '',
        'drop'  => '',
        'kills' => '',
        'path'  => '',
        'place' => '',
        'died'  => '',
        'end'   => ''
    ];

    protected $fillable = [
        'type',
        'drop',
        'kills',
        'path',
        'place',
        'died',
        'end'
    ];
}