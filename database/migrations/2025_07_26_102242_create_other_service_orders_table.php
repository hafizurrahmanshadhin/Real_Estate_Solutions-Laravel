<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('other_service_orders', function (Blueprint $table) {
            $table->id();

            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->string('email')->nullable(false);

            $table->unsignedBigInteger('other_services_id')->nullable(false);
            $table->foreign('other_services_id')->references('id')->on('other_services')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->text('additional_info')->nullable();
            $table->string('address')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('state')->nullable(false);
            $table->string('zip_code', 15)->nullable(false);

            $table->unsignedBigInteger('footage_size_id')->nullable(false);
            $table->foreign('footage_size_id')->references('id')->on('footage_sizes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('other_service_orders');
    }
};
