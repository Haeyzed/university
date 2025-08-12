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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('month');
            $table->string('year');

            $table->decimal('basic_salary', 10, 2);
            $table->decimal('total_allowance', 10, 2)->nullable();
            $table->decimal('total_deduction', 10, 2)->nullable();
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('net_salary', 10, 2);

            $table->tinyInteger('status')
                ->default(0)
                ->comment('0 = Unpaid, 1 = Paid');

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
        Schema::dropIfExists('payrolls');
    }
};
