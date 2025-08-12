<?php

use App\Models\CertificateTemplate;
use App\Models\Student;
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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(CertificateTemplate::class, 'template_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Student::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('serial_no')->nullable();
            $table->date('date');

            $table->string('starting_year')->nullable();
            $table->string('ending_year')->nullable();

            $table->decimal('credits', 5, 2);
            $table->decimal('point', 5, 2);

            $table->string('barcode')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
