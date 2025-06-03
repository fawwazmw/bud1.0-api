<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_device_id',
        'name',
        'location',
        'last_seen_at',
        'is_active',
        'notes',
        // 'user_id', // Jika Anda mengaktifkan relasi ke user
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    /**
     * Get all of the sensorReadings for the Device
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sensorReadings(): HasMany
    {
        return $this->hasMany(SensorReading::class);
    }
}
