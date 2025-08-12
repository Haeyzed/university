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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('payment_gateway')->default('none')->comment('paypal, stripe, razorpay, paystack, flutterwave, skrill, none');
            $table->string('paypal_client_id')->nullable();
            $table->string('paypal_secret')->nullable();
            $table->string('stripe_key')->nullable();
            $table->string('stripe_secret')->nullable();
            $table->string('razorpay_key')->nullable();
            $table->string('razorpay_secret')->nullable();
            $table->string('paystack_key')->nullable();
            $table->string('paystack_secret')->nullable();
            $table->string('merchant_email')->nullable(); // For Paystack
            $table->string('flutterwave_public_key')->nullable();
            $table->string('flutterwave_secret_key')->nullable();
            $table->string('flutterwave_secret_hash')->nullable();
            $table->string('skrill_email')->nullable();
            $table->string('skrill_secret')->nullable();
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
        Schema::dropIfExists('payment_settings');
    }
};
