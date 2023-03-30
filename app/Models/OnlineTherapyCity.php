<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class OnlineTherapyCity extends Model
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
        return Str::words(html_entity_decode(strip_tags($this->content)), 20);
    }

    public function getUrlAttribute(){
        return route('page.singleOnlineTherapy', $this->slug);
    }

}
