<?php

use App\Models\ItemCategory;
use App\Models\ItemStore;
use App\Models\ItemSupplier;
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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignIdFor(ItemCategory::class, 'category_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ItemSupplier::class, 'supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(ItemStore::class, 'store_id')->nullable()->constrained()->nullOnDelete();

            // Main fields
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->text('description')->nullable();

            // Status
            $table->boolean('status')->default(true);

            // Audit
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
