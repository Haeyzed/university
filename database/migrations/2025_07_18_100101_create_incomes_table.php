<?php

use App\Models\IncomeCategory;
use App\Models\User;
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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(IncomeCategory::class, 'category_id')->constrained('income_categories')->cascadeOnDelete();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->text('attach')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
