<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('created_by');

            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('deadline');

            $table->enum('status', ['Pending', 'In-Progress', 'Completed'])
                  ->default('Pending');

            // MULTIPLE USERS IDs ARRAY
            // e.g => [1, 5, 9]
            $table->json('assigned_to')
                  ->nullable()
                  ->default(json_encode(['Anyone']));

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('project_id')
                  ->references('id')->on('projects')
                  ->onDelete('cascade');

            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
