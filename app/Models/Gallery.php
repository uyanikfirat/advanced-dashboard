<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
  protected $guarded = [];
  protected $hidden = [];

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

  public function imageable()
  {
    return $this->morphTo();
  }
}
