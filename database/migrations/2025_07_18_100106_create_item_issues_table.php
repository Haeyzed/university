<?php

use App\Models\Item;
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
        Schema::create('item_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Item::class, 'item_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->unsignedInteger('quantity');
            $table->text('note')->nullable();
            $table->boolean('status')->default(false)->comment('false = Issued, true = Returned');
            $table->foreignIdFor(User::class,'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class,'updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_issues');
    }
};
