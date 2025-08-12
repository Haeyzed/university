<?php

use App\Models\FeesMaster;
use App\Models\StudentEnroll;
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
        Schema::create('fees_master_student_enroll', function (Blueprint $table) {
            $table->foreignIdFor(FeesMaster::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(StudentEnroll::class)
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_master_student_enroll');
    }
};
