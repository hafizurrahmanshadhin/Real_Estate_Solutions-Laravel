<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Contact Details
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->text('message')->nullable();
            $table->boolean('is_agreed_privacy_policy')->default(false);

            // Stripe information
            $table->string('stripe_session_id')->unique()->nullable(false);
            $table->string('stripe_payment_intent')->nullable(false);
            $table->string('payment_method')->nullable(false);
            $table->string('transaction_id')->nullable(false);
            $table->decimal('total_amount', 10, 2)->nullable(false);
            $table->string('currency', 10)->default('usd');
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
