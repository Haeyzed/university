<?php

use App\Models\FeesCategory;
use App\Models\FeesFine;
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
        Schema::create('fees_category_fees_fine', function (Blueprint $table) {
            $table->foreignIdFor(FeesCategory::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(FeesFine::class)
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_category_fees_fine');
    }
};
