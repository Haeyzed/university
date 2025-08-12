<?php

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
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('sms_gateway')->default('none')->comment('e.g., twilio, vonage, africastalking, textlocal, clickatell, smscountry');

            // Vonage (formerly Nexmo)
            $table->string('vonage_key')->nullable();
            $table->string('vonage_secret')->nullable();
            $table->string('vonage_number')->nullable();

            // Twilio
            $table->string('twilio_sid')->nullable();
            $table->string('twilio_auth_token')->nullable();
            $table->string('twilio_number')->nullable();

            // AfricasTalking
            $table->string('africas_talking_username')->nullable();
            $table->string('africas_talking_api_key')->nullable();

            // TextLocal
            $table->string('textlocal_key')->nullable();
            $table->string('textlocal_sender')->nullable();

            // Clickatell
            $table->string('clickatell_api_key')->nullable();

            // SMSCountry
            $table->string('smscountry_username')->nullable();
            $table->string('smscountry_password')->nullable();
            $table->string('smscountry_sender_id')->nullable();

            $table->boolean('status')->default(true);

            // Audit fields
            $table->foreignIdFor(User::class, 'created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // updated_by is nullable and only populated when a record is updated
            $table->foreignIdFor(User::class, 'updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_settings');
    }
};
