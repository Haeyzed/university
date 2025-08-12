<?php

use App\Models\Document;
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
        Schema::create('docables', function (Blueprint $table) {
            $table->foreignIdFor(Document::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('docable_id');
            $table->string('docable_type');

            $table->unique(['document_id', 'docable_id', 'docable_type'], 'docables_unique');
            $table->index(['docable_type', 'docable_id'], 'docables_type_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docables');
    }
};
