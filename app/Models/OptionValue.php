<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionValue extends Model
{
    protected $table = 'option_values';

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
