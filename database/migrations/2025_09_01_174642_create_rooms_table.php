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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // ชื่อห้อง
            $table->integer('capacity');           // จุได้กี่คน
            $table->text('instruments')->nullable(); // เครื่องดนตรี
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->json('available_times')->nullable(); // เก็บช่วงเวลาที่ห้องว่าง (เช่น ["09:00-12:00","13:00-17:00"])
            $table->decimal('price', 8, 2);        // ราคาต่อรอบ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
