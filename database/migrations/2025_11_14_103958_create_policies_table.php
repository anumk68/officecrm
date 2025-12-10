<?php
// database/migrations/2025_xx_xx_create_policies_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->nullable(); // e.g., "Policy", "Form", "Guideline"
            $table->text('description')->nullable();
            $table->string('file_path')->nullable(); // stored path in storage/app/public/policies/...
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};

