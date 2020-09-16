<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'movie_id',
        'order',
        'status',
        'user_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
