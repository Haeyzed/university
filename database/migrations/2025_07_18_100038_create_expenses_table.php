<?php

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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('expense_category_id')
                ->constrained('expense_categories')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->decimal('amount', 10, 2);
            $table->string('attachment_path')->nullable();

            $table->boolean('is_active')->default(true);

            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
