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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('source_id')
                ->nullable()
                ->constrained('enquiry_sources')
                ->nullOnDelete();

            $table->foreignId('reference_id')
                ->nullable()
                ->constrained('enquiry_references')
                ->nullOnDelete();

            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('date');
            $table->text('address')->nullable();
            $table->longText('note')->nullable();
            $table->text('attach')->nullable();

            $table->tinyInteger('status')
                ->default(1)
                ->comment('0: Rejected, 1: Pending, 2: Progress, 3: Approved');


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
        Schema::dropIfExists('enquiries');
    }
};
