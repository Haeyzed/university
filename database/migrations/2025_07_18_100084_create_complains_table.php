<?php

use App\Models\ComplainSource;
use App\Models\ComplainType;
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
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ComplainType::class, 'type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(ComplainSource::class, 'source_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('date');
            $table->text('action_taken')->nullable();
            $table->string('assigned')->nullable();
            $table->longText('issue')->nullable();
            $table->text('note')->nullable();
            $table->text('attach')->nullable();

            $table->tinyInteger('status')
                ->default(1)
                ->comment('0 = Rejected, 1 = Pending, 2 = In Progress, 3 = Resolved');

            $table->foreignIdFor(User::class,'created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignIdFor(User::class,'updated_by')
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
        Schema::dropIfExists('complains');
    }
};
