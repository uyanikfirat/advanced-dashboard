<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];


    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function specificResourcePath()
    {
        return 'admin/categories/' . $this->id;
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function getUrlAttribute(){
        return route('single.category', $this->slug);
    }
}
