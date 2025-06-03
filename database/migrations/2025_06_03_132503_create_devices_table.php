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
        Schema::create('devices', function (Blueprint $table) {
            $table->id(); // Primary key (BigInt, Unsigned, Auto Increment)
            $table->string('unique_device_id')->unique(); // ID unik dari perangkat, misal MAC address atau ID kustom
            $table->string('name'); // Nama perangkat agar mudah dikenali, misal "Sensor Ruang Tamu"
            $table->string('location')->nullable(); // Lokasi perangkat (opsional)
            // Jika Anda ingin menghubungkan perangkat ke pengguna tertentu di tabel 'users'
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('last_seen_at')->nullable(); // Kapan terakhir data diterima dari perangkat ini
            $table->boolean('is_active')->default(true); // Status perangkat
            $table->text('notes')->nullable(); // Catatan tambahan tentang perangkat
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
