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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            // LINK APPROVED LEAD
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->nullOnDelete();
            // ------------------------
            // BASIC PROJECT INFO
            // ------------------------
            $table->string('project_name');
            $table->string('company_name')->nullable();
            $table->string('service_type')->nullable();
            $table->string('sub_service')->nullable();

            $table->date('deadline');
            $table->string('priority')->default('Medium');
            $table->enum('status', ['active', 'done', 'on-hold'])->default('active');
            $table->string('color')->nullable();

            // ------------------------
            // DOMAIN DETAILS
            // ------------------------
            $table->string('domain_name')->nullable();
            $table->string('domain_registrar')->nullable();
            $table->date('domain_expiry')->nullable();

            // ------------------------
            // HOSTING DETAILS
            // ------------------------
            $table->string('hosting_provider')->nullable();
            $table->string('server_type')->nullable();
            $table->date('hosting_expiry')->nullable();
            $table->string('cpanel_url')->nullable();
            $table->string('cpanel_username')->nullable();
            $table->string('cpanel_password')->nullable();

            // ------------------------
            // CONFIDENTIAL CREDENTIALS
            // ------------------------
            $table->string('project_email')->nullable();
            $table->string('project_email_password')->nullable();

            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();

            $table->string('backup_email')->nullable();

            $table->string('admin_url')->nullable();
            $table->string('admin_username')->nullable();
            $table->string('admin_password')->nullable();

            $table->text('other_credentials')->nullable();

            // ------------------------
            // DEFAULT FIELDS
            // ------------------------
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
