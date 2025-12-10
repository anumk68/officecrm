<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->json('to')->nullable();
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->string('subject')->nullable();
            $table->longText('message')->nullable();
            $table->boolean('is_draft')->default(true);
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
