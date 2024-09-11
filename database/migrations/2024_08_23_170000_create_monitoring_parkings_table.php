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
        Schema::create('monitoring_parkings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_area_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('used')->default(0);
            $table->integer('available')->nullable();
            $table->string('slug');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_parkings');
    }
};
