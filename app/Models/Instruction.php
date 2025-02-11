<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'instruction_name',
        'tts_description',
        'require_action', // Added field
        'type',
        'waiting_time' // Added field
    ];

    // Cast para parámetros JSON (si se accede desde el pivot)
    protected $casts = [
        'parametros' => 'array' // Solo si Instruction tiene relación directa
    ];

    // Relación con los escenarios (incluyendo campos del pivot)
    public function scenarios()
    {
        return $this->belongsToMany(Scenario::class, 'scenario_instruction')
                    ->using(ScenarioInstruction::class)
                    ->withPivot('order', 'repeticiones', 'parametros');
    }
}