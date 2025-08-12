<?php

use App\Models\Program;
use App\Models\Session;
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
        Schema::create('program_session', function (Blueprint $table) {
            $table->foreignIdFor(Program::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Session::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['program_id', 'session_id'], 'program_session_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_session');
    }
};
