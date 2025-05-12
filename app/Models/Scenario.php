<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scenario extends Model
{
    use HasFactory;

    protected $primaryKey = 'scenario_id';

    protected $fillable = [
        'scenario_name',
        'image_url',
        'is_enabled',
    ];

    public function instructions()
    {
        return $this->belongsToMany(Instruction::class, 'scenario_instruction', 'scenario_id', 'instruction_id')
                    ->withPivot('order', 'reps', 'params')
                    ->withTimestamps();
    }
}