<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lead_id');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();

            $table->unsignedBigInteger('participant_id')->nullable()->comment('table name - contact_person');
            $table->foreign('participant_id')->references('id')->on('contact_persons')->onDelete('set null');

            $table->dateTime('schedule_from');
            $table->dateTime('schedule_to');
            $table->string('location')->nullable();

            // ✅ New fields
            $table->boolean('is_done')->default(false)->comment('0 = pending, 1 = completed');
            $table->string('activity_type')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
