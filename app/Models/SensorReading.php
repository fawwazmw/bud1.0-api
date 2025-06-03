<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'co2_ppm',
        'temperature_c',
        'humidity_percent',
        'air_quality_level',
        'voltage_sensor',
        'created_at', // Jika Anda ingin mengontrol created_at dari ESP32
    ];

    protected $casts = [
        'co2_ppm' => 'float',
        'temperature_c' => 'float',
        'humidity_percent' => 'float',
        'air_quality_level' => 'integer',
        'voltage_sensor' => 'float',
    ];

    /**
     * Get the device that owns the SensorReading
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
