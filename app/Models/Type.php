<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use SoftDeletes;
    protected $table = 'types';

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
