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
        Schema::create('parking_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('parking_area_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->dateTime('entry_time');
            $table->dateTime('exit_time');
            $table->unsignedInteger('duration')->nullable();
            $table->decimal('total_cost',10,2)->nullable();
            $table->enum('status', ['active', 'completed']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_sessions');
    }
};
