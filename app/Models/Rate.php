<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'point',
        'movie_id',
        'user_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
