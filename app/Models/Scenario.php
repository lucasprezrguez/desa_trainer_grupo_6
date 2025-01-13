<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discharge_numbers',
        'min_interval',
    ];

    public function instructions()
    {
        return $this->belongsToMany(Instruction::class, 'scenario_instruction');
    }
}
