<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostTag extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['tag'];
    protected $hidden = [];

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }


    
}
