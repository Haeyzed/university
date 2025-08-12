<?php

use App\Models\MeetingType;
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
        Schema::create('meeting_schedules', function (Blueprint $table) {
            $table->id();

            // Foreign key to meeting_types table
            $table->foreignIdFor(MeetingType::class,'type_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->longText('description')->nullable();
            $table->date('date');
            $table->time('time');
            $table->string('venue')->nullable();
            $table->text('attach')->nullable();
            $table->boolean('status')->default(true);

            // Foreign keys to users table (nullable, set null on delete)
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
        Schema::dropIfExists('meeting_schedules');
    }
};
