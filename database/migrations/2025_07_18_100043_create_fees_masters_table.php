<?php

use App\Models\Faculty;
use App\Models\FeesCategory;
use App\Models\Program;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Session;
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
        Schema::create('fees_masters', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(FeesCategory::class, 'category_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Faculty::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Program::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Session::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Semester::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Section::class)->constrained()->cascadeOnDelete();

            $table->decimal('amount', 10, 2);
            $table->tinyInteger('type')->default('1')->comment('1 Fixed, 2 Per Credit');
            $table->date('assign_date');
            $table->date('due_date');
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
        Schema::dropIfExists('fees_masters');
    }
};
