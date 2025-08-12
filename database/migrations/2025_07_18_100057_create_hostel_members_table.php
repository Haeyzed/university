<?php

use App\Models\Hostel;
use App\Models\HostelRoom;
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
        Schema::create('hostel_members', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Hostel::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(HostelRoom::class, 'room_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->date('join_date');
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('hostel_members');
    }
};
