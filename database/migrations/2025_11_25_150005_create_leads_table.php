<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->string('lead_id')->unique();
            $table->string('fb_lead_unique_id')->nullable();   // id
            $table->string('created_time')->nullable();
            $table->string('ad_id')->nullable();
            $table->string('ad_name')->nullable();
            // $table->string('adset_id')->nullable();
            $table->string('adset_name')->nullable();
            // $table->string('campaign_id')->nullable();
            // $table->string('campaign_name')->nullable();
            // $table->string('form_id')->nullable();
            // $table->string('form_name')->nullable();
            // $table->string('is_organic')->nullable();
            $table->string('platform')->nullable();

            $table->string('email')->nullable()->index();
            $table->string('full_name')->nullable();
            $table->string('phone_number')->nullable()->index();
            $table->string('city')->nullable();

            // CRM Extra Fields
            $table->foreignId('lead_source_id')->nullable()->constrained('lead_sources')->nullOnDelete();
            $table->string('status')->default('new');
            $table->enum('color', ['dark_grey','red','orange','green','white'])->default('white');
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            // Extra data storage
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('leads');
    }
};
