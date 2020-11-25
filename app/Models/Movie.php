<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'name_origin',
        'description',
        'nominations',
        'card_cover',
        'age',
        'slug',
        'genre',
        'runtime',
        'release_year',
        'quality',
        'country',
        'rate',
        'user_id',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function types()
    {
        return $this->belongsToMany(Type::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
