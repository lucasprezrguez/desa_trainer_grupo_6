<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScenarioInstruction extends Pivot
{
    use HasFactory;

    protected $table = 'scenario_instruction';

    protected $fillable = [
        'scenario_id',
        'instruction_id',
        'order',
        'repeticiones',
        'parametros'
    ];

    protected $casts = [
        'parametros' => 'array'
    ];
}