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
        Schema::create('bills', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('booking_id')->nullable()->constrained()->onDelete('cascade');
        $table->string('bill_code')->unique();
        $table->decimal('total_amount', 10, 2);
        $table->string('payment_method')->nullable();
        $table->string('payment_gateway')->nullable();   // ← sudah benar sesuai ERD
        $table->enum('status', ['belum_dibayar', 'verifikasi_tertunda', 'dibayar', 'terlambat'])->default('belum_dibayar');
        $table->timestamps();

            
            // Indexes
            $table->index('user_id');
            $table->index('booking_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
