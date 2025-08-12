<?php

use App\Models\PostalExchangeType;
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
        Schema::create('postal_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PostalExchangeType::class, 'type_id')->constrained()->cascadeOnDelete();
            $table->string('from');
            $table->string('to');
            $table->string('reference_number')->nullable(); // renamed for clarity
            $table->date('exchange_date'); // renamed from date
            $table->text('attachment')->nullable(); // renamed from attach
            $table->longText('note')->nullable();
            $table->boolean('is_active')->default(true); // renamed from status
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
        Schema::dropIfExists('postal_exchanges');
    }
};
