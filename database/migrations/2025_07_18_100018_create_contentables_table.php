<?php

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
        Schema::create('contentables', function (Blueprint $table) {
            $table->foreignId('content_id')
                ->constrained('contents')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('contentable_id');
            $table->string('contentable_type');

            $table->unique(['content_id', 'contentable_id', 'contentable_type'], 'contentables_unique');
            $table->index(['contentable_type', 'contentable_id'], 'contentables_type_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contentables');
    }
};
