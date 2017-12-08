<?php
/**
 * Created by PhpStorm.
 * User: delta
 * Date: 9/21/2017
 * Time: 9:55 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Memory extends Model {

    protected static $rules = [
        'title'         => '',
        'summary'       => '',
        'content'       => '',
        'thumbnail_url' => '',
        'memory_date'   => '',
        'created_at'    => ''
    ];

    protected $fillable = [
        'title',
        'summary',
        'content',
        'thumbnail_url',
        'created_at',
        'memory_date'
    ];
}