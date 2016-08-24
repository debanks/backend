<?php

namespace App\Models;

class Article
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'content', 'type', 'thumbnail_url', 'large_image_url'
    ];
}
