<?php

use App\Enums\StatusEnum;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_area_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('parking_rate_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('license_plate');
            $table->string('no_ticket')->nullable();
            $table->string('slug');
            $table->dateTime('entry_time');
            $table->dateTime('exit_time')->nullable();
            $table->integer('duration')->nullable();
            $table->decimal('total_cost',10,2)->nullable();
            $table->decimal('payment',10,2)->nullable();
            $table->decimal('change_pay',10,2)->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::ACTIVE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
