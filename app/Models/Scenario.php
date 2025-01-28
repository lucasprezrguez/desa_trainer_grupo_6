<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'scenario_name', // Renombrado de 'name'
        'image_url'      // Nueva columna
    ];

    // Relación con las instrucciones (incluyendo campos del pivot)
    public function instructions()
    {
        return $this->belongsToMany(Instruction::class, 'scenario_instruction')
                    ->using(ScenarioInstruction::class)
                    ->withPivot('order', 'repeticiones', 'parametros');
    }
}