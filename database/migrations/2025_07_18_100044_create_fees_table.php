<?php

use App\Models\FeesCategory;
use App\Models\FeesDiscount;
use App\Models\FeesFine;
use App\Models\FeesMaster;
use App\Models\StudentEnroll;
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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(FeesMaster::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(StudentEnroll::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(FeesCategory::class, 'category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(FeesDiscount::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignIdFor(FeesFine::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('fine_amount', 10, 2)->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->decimal('due_amount', 10, 2)->nullable();
            $table->date('date');

            $table->tinyInteger('status')
                ->default(0)
                ->comment('0 = Unpaid, 1 = Paid, 2 = Partial');

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
        Schema::dropIfExists('fees');
    }
};
