<?php

namespace App\Models;

use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Service extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];
    protected $hidden = [];



    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

  
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }



    private static function allowedFieldsForOrder()
    {
        return ['created_at', 'user_id'];
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getDateAttribute()
    {
        return substr($this->created_at, 0, 10);
    }

    public function getCleanedContentAttribute(){
        $length = 190;
        return Str::substr(html_entity_decode(strip_tags($this->content)),0, $length);
    }

    public function getUrlAttribute(){
        return route('single.service', $this->slug);
    }

    public function images()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }

    public function image()
    {
        return $this->morphOne(Gallery::class, 'imageable');
    }


}
