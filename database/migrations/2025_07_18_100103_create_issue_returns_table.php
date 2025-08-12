<?php

use App\Models\Book;
use App\Models\LibraryMember;
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
        Schema::create('issue_returns', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Book::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(LibraryMember::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();

            $table->decimal('fine_amount', 10, 2)->nullable();

            $table->tinyInteger('status')
                ->default(0)
                ->comment('0 = Issued, 1 = Returned, 2 = Lost');

            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_returns');
    }
};
