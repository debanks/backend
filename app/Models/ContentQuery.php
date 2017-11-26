<?php
/**
 * Created by PhpStorm.
 * User: delta
 * Date: 9/21/2017
 * Time: 9:55 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ContentQuery extends Model {

    public $table = 'content_query';

    protected static $rules = [
        'user_id'        => '',
        'type'           => '',
        'item_id'        => '',
        'featured'       => '',
        'headline'       => '',
        'description'    => '',
        'thumbnail_url'  => '',
        'content'        => '',
        'created_at'     => '',
        'user_name'      => '',
        'link_item_type' => '',
        'link_item_id'   => '',
        'link_item_name' => '',
        'meta_data_1'    => '',
        'meta_data_2'    => ''
    ];

    protected $fillable = [
        'user_id',
        'link_item_name',
        'link_item_type',
        'type',
        'item_id',
        'featured',
        'headline',
        'description',
        'thumbnail_url',
        'content',
        'created_at',
        'link_item_id',
        'user_name',
        'meta_data_1',
        'meta_data_2'
    ];
}