<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dbanks
 * Date: 7/5/17
 * Time: 11:11 AM
 */
class Project extends Model {

    protected static $rules = [
        'tag'             => '',
        'name'            => '',
        'description'     => '',
        'start_date'      => '',
        'end_date'        => '',
        'languages'       => '',
        'about'           => '',
        'image_url'       => '',
        'featured'        => '',
        'large_image_url' => '',
        'employer'        => '',
        'github'          => '',
        'color'           => ''
    ];

    protected $fillable = [
        'tag',
        'name',
        'description',
        'start_date',
        'end_date',
        'languages',
        'about',
        'image_url',
        'large_image_url',
        'featured',
        'employer',
        'github',
        'color'
    ];
}