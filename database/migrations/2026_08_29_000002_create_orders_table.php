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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('balance_package_id')->constrained('balance_packages')->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('telegram_username');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USDT');
            $table->string('payment_network', 20)->default('TRC20');
            $table->string('payment_address');
            $table->enum('payment_status', ['pending', 'submitted', 'verified', 'rejected'])->default('pending');
            $table->enum('verification_status', ['waiting', 'under_review', 'approved', 'rejected'])->default('waiting');
            $table->string('transaction_ref')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
