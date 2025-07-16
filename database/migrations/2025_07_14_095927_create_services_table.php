<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('package_id')->nullable();
            $table->foreign('package_id')->references('id')->on('packages')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('footage_size_id')->nullable();
            $table->foreign('footage_size_id')->references('id')->on('footage_sizes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->decimal('price', 10, 2)->default(0);

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('services');
    }
};
