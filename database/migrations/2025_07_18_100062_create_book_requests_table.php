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
        Schema::create('book_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('book_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('isbn')->nullable();
            $table->string('code')->nullable();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('edition')->nullable();
            $table->string('publish_year')->nullable();
            $table->string('language')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('request_by')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->longText('description')->nullable();
            $table->text('note')->nullable();
            $table->text('attach')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 Rejected, 1 Pending, 2 Progress, 3 Approved');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_requests');
    }
};
