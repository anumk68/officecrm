<?php
// database/migrations/2025_xx_xx_create_salary_slips_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up()
    {
        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();

            // Company Info
            $table->string('company_logo')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_tagline')->nullable();
            $table->text('company_address')->nullable();

            // Employee Info
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('emp_code')->nullable();
            $table->string('designation')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('bank_account')->nullable();

            // Month
            $table->string('month');

            // Salary
            $table->decimal('basic', 10, 2)->default(0);
            $table->decimal('incentives', 10, 2)->default(0);
            $table->decimal('overtime', 10, 2)->default(0);

            // Deduction
            $table->decimal('unpaid_leave_amount', 10, 2)->default(0);
            $table->decimal('late_coming', 10, 2)->default(0);

            // Attendance
            $table->integer('working_days')->default(0);
            $table->integer('on_duty')->default(0);
            $table->integer('unpaid_leave_days')->default(0);

            // Personal Information
            $table->string('father_name')->nullable();
            $table->string('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('file_path')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_slips');
    }
};

