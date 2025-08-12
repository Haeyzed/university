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
        Schema::create('result_contributions', function (Blueprint $table) {
            $table->id();
            $table->decimal('attendances',5,2)->default('0');
            $table->decimal('assignments',5,2)->default('0');
            $table->decimal('activities',5,2)->default('0');
            $table->boolean('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_contributions');
    }
};
