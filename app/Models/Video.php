<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'tags',
        'description',
        'movies_id',
        'playlist_id',
        'status',
        'slug',
        'user_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
