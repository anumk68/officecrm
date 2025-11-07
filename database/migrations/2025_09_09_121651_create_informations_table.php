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
        Schema::create('informations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['announcement','policy','event','holiday','general'])->default('general');
            $table->string('attachment')->nullable();
            $table->enum('visible_to', ['all','manager','team_leader','team_member','hr'])->default('all');
            $table->enum('status', ['active','inactive'])->default('active');
               $table->timestamp('information_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();


            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informations');
    }
};
