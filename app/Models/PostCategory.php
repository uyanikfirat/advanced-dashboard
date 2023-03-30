<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['post', 'category'];
    protected $hidden = [];

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
