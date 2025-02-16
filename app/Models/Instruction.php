<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instruction extends Model
{
    use HasFactory;

    protected $primaryKey = 'instruction_id';

    protected $fillable = [
        'instruction_name',
        'require_action',
        'waiting_time'
    ];

    protected $casts = [
        'params' => 'array'
    ];

    public function scenarios()
    {
        return $this->belongsToMany(Scenario::class, 'scenario_instruction', 'instruction_id', 'scenario_id')
                    ->withPivot('order', 'reps', 'params')
                    ->withTimestamps();
    }
}