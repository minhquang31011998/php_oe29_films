<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'link',
        'description',
        'status',
        'user_id',
    ];

    public function sources()
    {
        return $this->hasMany(Source::class);
    }
}
