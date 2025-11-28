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
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('size', 50)->nullable();
            $table->enum('status', ['tersedia', 'terisi', 'pemeliharaan', 'sudah_dipesan'])->default('tersedia');
            $table->text('fasilitas')->nullable();
            $table->enum('stok', ['tersedia', 'tidak_tersedia'])->default('tersedia');
            $table->string('main_image_url')->nullable();
            $table->text('icon_svg')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('price');
            $table->index('status');
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
