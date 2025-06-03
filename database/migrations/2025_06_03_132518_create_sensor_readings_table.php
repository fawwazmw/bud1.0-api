<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id(); // Primary key

            // Foreign key ke tabel 'devices'
            // Ini mengasumsikan setiap pembacaan sensor berasal dari perangkat yang terdaftar
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');

            // Data sensor dari ESP32
            // Gunakan double untuk presisi yang lebih baik untuk data sensor float
            $table->double('co2_ppm', 8, 2)->nullable(); // 8 total digit, 2 digit desimal
            $table->double('temperature_c', 8, 2)->nullable();
            $table->double('humidity_percent', 8, 2)->nullable();
            $table->unsignedTinyInteger('air_quality_level')->nullable(); // Untuk level 0-3 (atau lebih jika perlu)
            $table->double('voltage_sensor', 8, 3)->nullable(); // Misal 3 digit desimal untuk voltase

            // Timestamp bisa diisi otomatis oleh Laravel jika menggunakan Eloquent create()
            // atau diisi manual saat data diterima dari ESP32.
            // `timestamps()` akan membuat created_at dan updated_at.
            // Jika Anda ingin timestamp spesifik dari ESP32 (misalnya jika ESP32 punya RTC/NTP)
            // Anda bisa menambahkan $table->timestamp('recorded_at'); dan mengisinya dari ESP32.
            // Jika tidak, created_at dari timestamps() akan mencatat waktu data dimasukkan ke database.
            $table->timestamps();

            // Index untuk performa query yang lebih baik
            $table->index('device_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};
