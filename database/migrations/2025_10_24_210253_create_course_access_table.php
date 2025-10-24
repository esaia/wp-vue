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
        Schema::create('course_access', function (Blueprint $table) {
            $table->id();

            $table->string('user_email');
            $table->string('product_id'); // DodoPayments product ID
            $table->string('payment_id'); // DodoPayments payment ID
            $table->string('checkout_session_id')->nullable(); // DodoPayments session ID
            $table->timestamp('access_granted_at');
            $table->timestamp('access_expires_at')->nullable(); // For limited-time access
            $table->enum('status', ['active', 'expired', 'pending'])->default('active');
            $table->json('metadata')->nullable(); // Store additional payment data

            // Indexes for better performance
            $table->index(['user_email', 'status']);
            $table->index(['product_id', 'status']);
            $table->index(['payment_id']);
            $table->index(['access_expires_at']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_access');
    }
};
