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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT

            $table->string('subject');
            $table->string('description')->nullable();

            $table->json('billing_address')->nullable();
            $table->json('shipping_address')->nullable();

            $table->decimal('discount_percent', 12, 4)->default(0)->nullable();
            $table->decimal('discount_amount', 12, 4)->nullable();
            $table->decimal('tax_amount', 12, 4)->nullable();
            $table->decimal('adjustment_amount', 12, 4)->nullable();
            $table->decimal('sub_total', 12, 4)->nullable();
            $table->decimal('grand_total', 12, 4)->nullable();

            $table->datetime('expired_at')->nullable();

            $table->foreignId('person_id')
                ->constrained('contact_persons')
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
