<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon; // Untuk timestamp

class SensorDataController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data yang masuk dari ESP32
        $validator = Validator::make($request->all(), [
            'unique_device_id' => 'required|string|max:255',
            'co2_ppm' => 'required|numeric',
            'temperature_c' => 'required|numeric',
            'humidity_percent' => 'required|numeric|min:0|max:100',
            'air_quality_level' => 'required|integer|min:0|max:3', // Asumsi level 0-3
            'voltage_sensor' => 'sometimes|numeric', // Opsional, jika ada
            // Anda bisa menambahkan validasi untuk nama perangkat, lokasi, dll. jika dikirim dari ESP32 saat pendaftaran pertama
            'device_name' => 'sometimes|string|max:255',
            'device_location' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Unprocessable Entity
        }

        // Cari atau buat perangkat baru berdasarkan unique_device_id
        $device = Device::updateOrCreate(
            ['unique_device_id' => $request->input('unique_device_id')],
            [
                'name' => $request->input('device_name', 'Device ' . $request->input('unique_device_id')), // Default name jika tidak ada
                'location' => $request->input('device_location'),
                'last_seen_at' => Carbon::now(), // Update waktu terakhir terlihat
                'is_active' => true,
            ]
        );

        // Simpan pembacaan sensor baru
        $sensorReading = new SensorReading();
        $sensorReading->device_id = $device->id;
        $sensorReading->co2_ppm = $request->input('co2_ppm');
        $sensorReading->temperature_c = $request->input('temperature_c');
        $sensorReading->humidity_percent = $request->input('humidity_percent');
        $sensorReading->air_quality_level = $request->input('air_quality_level');

        if ($request->has('voltage_sensor')) {
            $sensorReading->voltage_sensor = $request->input('voltage_sensor');
        }
        // created_at dan updated_at akan diisi otomatis oleh Eloquent

        $sensorReading->save();

        return response()->json([
            'message' => 'Data received and stored successfully!',
            'device_id' => $device->id,
            'reading_id' => $sensorReading->id
        ], 201); // 201 Created
    }
}
