<?php

use App\Models\ContentType;
use App\Models\Faculty;
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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Faculty::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Program::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Session::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Semester::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Section::class)->nullable()->constrained()->nullOnDelete();

            $table->foreignIdFor(ContentType::class, 'type_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->longText('description')->nullable();
            $table->date('date');
            $table->text('url')->nullable();
            $table->text('attach')->nullable();
            $table->boolean('status')->default(true);

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
        Schema::dropIfExists('contents');
    }
};
