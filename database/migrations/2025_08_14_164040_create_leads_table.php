<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_person_id');
            $table->unsignedBigInteger('lead_product_id');
            $table->string('lead_title');
            $table->enum('status', ['new', 'in_progress', 'won', 'lost'])->default('new');
            $table->decimal('lead_value', 10, 2)->nullable();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('contact_person_id')->references('id')->on('contact_persons')->onDelete('cascade');
            $table->foreign('lead_product_id')->references('id')->on('lead_products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }


};
