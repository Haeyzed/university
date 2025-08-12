<?php

use App\Models\User;
use App\Models\VisitPurpose;
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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VisitPurpose::class, 'purpose_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('id_card')->nullable();
            $table->string('company_name')->nullable();
            $table->string('total_persons')->nullable();
            $table->date('date');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->longText('note')->nullable();
            $table->text('attachment')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
