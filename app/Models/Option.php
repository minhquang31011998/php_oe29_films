<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    public function optionValues()
    {
        return $this->hasMany(OptionValue::class);
    }
}
