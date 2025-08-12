<?php

use App\Models\Expense;
use App\Models\Fee;
use App\Models\Income;
use App\Models\Payroll;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Fee::class, 'fees_id')->nullable()->constrained('fees')->nullOnDelete();
            $table->foreignIdFor(Income::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Expense::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Payroll::class)->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->tinyInteger('type')->comment('1 Income, 2 Expense');
            $table->date('date');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
