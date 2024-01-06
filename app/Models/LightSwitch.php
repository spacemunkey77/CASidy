<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LightSwitch extends Model
{
    protected $table = 'lightswitches';

    public function lights()
    {
        return $this->belongsToMany(Light::class);
    }
}
