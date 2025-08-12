<?php

use App\Models\FeesDiscount;
use App\Models\StatusType;
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
        Schema::create('fees_discount_status_type', function (Blueprint $table) {
            $table->foreignIdFor(FeesDiscount::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(StatusType::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['fees_discount_id', 'status_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_discount_status_type');
    }
};
