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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('category_id')->constrained('book_categories')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // Book Details
            $table->string('title');
            $table->string('isbn')->nullable()->unique();
            $table->string('code')->nullable()->unique();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('edition')->nullable();
            $table->string('publish_year')->nullable();
            $table->string('language')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('quantity')->nullable();

            // Physical Location
            $table->string('section')->nullable();
            $table->string('column')->nullable();
            $table->string('row')->nullable();

            // Additional Info
            $table->longText('description')->nullable();
            $table->text('note')->nullable();
            $table->text('attach')->nullable();

            // Status
            $table->tinyInteger('status')->default(1)->comment('0 = Lost, 1 = Available, 2 = Damaged');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
