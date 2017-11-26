<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Game extends Model {

    protected static $rules = [
        'tag'               => '',
        'name'              => '',
        'description'       => '',
        'release_date'      => '',
        'system'            => '',
        'review'            => '',
        'score'             => '',
        'image_url'         => '',
        'large_image_url'   => '',
        'currently_playing' => '',
        'time_to_beat'      => '',
        'playtime'          => '',
        'featured'          => ''
    ];

    protected $fillable = [
        'tag',
        'name',
        'description',
        'release_date',
        'system',
        'review',
        'score',
        'image_url',
        'large_image_url',
        'currently_playing',
        'time_to_beat',
        'playtime',
        'featured'
    ];
}