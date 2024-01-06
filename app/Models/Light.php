<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Light extends Model
{
    protected $table = 'lights';

    public function switches()
    {
        return $this->belongsToMany(LightSwitch::class);
    }
}
