<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subheading',
        'description',
        'art_ids',
        'user_id',
        'art_channel_id',
    ];

    public function users() {
        return $this->hasMany('App\Models\User','id');
    }

    public function art_channels() {
        return $this->hasMany('App\Models\ArtChannel','id');
    }
}
