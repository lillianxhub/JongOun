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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');

            $table->string('phone');               // เบอร์โทร
            $table->string('band_name')->nullable(); // ชื่อวงดนตรี
            $table->integer('members')->default(1); // จำนวนสมาชิก
            $table->text('additional_request')->nullable(); // ความต้องการพิเศษ

            $table->decimal('total_price', 10, 2); // ราคารวม
            $table->date('date');                  // วันที่
            $table->time('start_time');            // เวลาเริ่ม
            $table->time('end_time');              // เวลาสิ้นสุด

            $table->enum('status', ['pending', 'approved', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
