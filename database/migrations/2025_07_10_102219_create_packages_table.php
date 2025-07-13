<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable(false);
            $table->string('image')->nullable(false);
            $table->string('name')->nullable(false);
            $table->text('description')->nullable(false);
            $table->boolean('is_popular')->default(false)->nullable(false);

            $table->enum('status', ['active', 'inactive'])->default('active')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('packages');
    }
};
