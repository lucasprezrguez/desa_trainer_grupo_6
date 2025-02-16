<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScenarioInstruction extends Model
{
    use HasFactory;

    protected $table = 'scenario_instruction';

    protected $fillable = [
        'scenario_id',
        'instruction_id',
        'order',
        'reps',
        'params'
    ];

    protected $casts = [
        'params' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function scenario()
    {
        return $this->belongsTo(Scenario::class, 'scenario_id');
    }

    public function instruction()
    {
        return $this->belongsTo(Instruction::class, 'instruction_id');
    }
}