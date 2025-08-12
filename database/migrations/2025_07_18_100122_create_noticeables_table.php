<?php

use App\Models\Notice;
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
        Schema::create('noticeables', function (Blueprint $table) {
            $table->foreignIdFor(Notice::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('noticeable_id');
            $table->string('noticeable_type');

            $table->unique(['notice_id', 'noticeable_id', 'noticeable_type'], 'noticeables_unique');
            $table->index(['noticeable_type', 'noticeable_id'], 'noticeables_type_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticeables');
    }
};
