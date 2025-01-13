<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'discharges',
        'discharge_interval',
        'tempo',
        'basic_rcp_duration',
        'non_indicated_rcp_duration',
        'predetermined_scenario',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
