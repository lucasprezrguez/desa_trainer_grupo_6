<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'on_led',
        'pause_state',
        'display_message',
    ];

    public function parametros()
    {
        return $this->hasMany(Parameter::class);
    }
}