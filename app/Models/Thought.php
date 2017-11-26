<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Thought extends Model {

    protected static $rules = [
        'user_id'    => '',
        'thought'    => '',
        'featured'   => '',
        'created_at' => '',
        'item_type'  => '',
        'item_id'    => '',
        'item_name'  => '',
        'image_url'  => '',
        'tag'        => ''
    ];

    protected $fillable = [
        'user_id',
        'thought',
        'featured',
        'created_at',
        'image_url',
        'item_id',
        'item_type',
        'item_name',
        'tag'
    ];
}