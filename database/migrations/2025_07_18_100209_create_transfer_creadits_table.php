<?php

use App\Models\StudentEnroll;
use App\Models\Subject;
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
        Schema::create('transfer_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StudentEnroll::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->decimal('credit_hour', 5, 2);
            $table->longText('note')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('transfer_credits');
    }
};
