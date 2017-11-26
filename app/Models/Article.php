<?php
/**
 * Created by PhpStorm.
 * User: delta
 * Date: 9/21/2017
 * Time: 9:55 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Article extends Model {

    protected static $rules = [
        'user_id'         => '',
        'game'            => '',
        'item_type'       => '',
        'item_id'         => '',
        'item_name'       => '',
        'title'           => '',
        'featured'        => '',
        'summary'         => '',
        'content'         => '',
        'thumbnail_url'   => '',
        'link_url'        => '',
        'sticky'          => '',
        'created_at'      => ''
    ];

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'featured',
        'summary',
        'content',
        'thumbnail_url',
        'link_url',
        'sticky',
        'created_at',
        'item_id',
        'item_type',
        'item_name'
    ];
}