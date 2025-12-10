<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lead_imports', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->string('original_name')->nullable();
            $table->integer('total_rows')->default(0);
            $table->integer('successful_rows')->default(0);
            
            $table->json('failed_details')->nullable();  
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->longText('valid_rows')->nullable();
            $table->longText('failed_rows')->nullable();
              $table->longText('mapping')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_imports');
    }
};
