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
        Schema::create('marksheet_settings', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->text('title')->nullable();
            $table->text('header_left')->nullable();
            $table->text('header_center')->nullable();
            $table->text('header_right')->nullable();
            $table->longText('body')->nullable();
            $table->text('footer_left')->nullable();
            $table->text('footer_center')->nullable();
            $table->text('footer_right')->nullable();
            $table->text('logo_left')->nullable();
            $table->text('logo_right')->nullable();
            $table->text('background')->nullable();
            $table->string('width')->default('auto');
            $table->string('height')->default('auto');
            $table->boolean('student_photo')->default(0);
            $table->boolean('barcode')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marksheet_settings');
    }
};
