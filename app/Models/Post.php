<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];
    protected $with = ['user', 'category'];
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

    public function scopeRecent($query)
    {
        return $query->where('type','post')->where('created_at', '>', (new \Carbon\Carbon)->submonths(1) );
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\PostCategory');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\PostTag');
    }

    public function images()
    {
        return $this->morphMany('App\Models\Gallery', 'imageable');
    }

    private static function allowedFieldsForOrder()
    {
        return ['votes', 'created_at', 'user_id'];
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

    public function getEstimateReadAttribute(){
        $word = str_word_count(strip_tags($this->content));
        $m = floor($word / 200);
        return $m . ' dk';
    }


    public function getUrlAttribute(){
        return $this->type === 'post' ? route('single.post', [$this->category->slug, $this->slug]) : settings('site_url') .'/'.$this->slug ;
    }


    public function getCategoryIdAttribute($catId)
    {
        return empty($catId) ? null : (int)$catId;
    }


}
