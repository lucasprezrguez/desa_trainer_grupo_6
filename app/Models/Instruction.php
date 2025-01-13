<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'text_content',
        'require_action',
        'action_type',
        'waiting_time',
    ];

    public function scenarios()
    {
        return $this->belongsToMany(Scenario::class, 'scenario_instruction');
    }
}
