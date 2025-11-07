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
        Schema::table('users', function (Blueprint $table) {
            $table->string('unique_id')->unique()->after('id');
            $table->date('joining_date')->nullable()->after('profile_pic');
            $table->date('dob')->nullable()->after('joining_date');
            $table->string('pan_card')->nullable()->after('dob'); // file path
            $table->string('aadhaar_card')->nullable()->after('pan_card'); // file path
            $table->string('last_qualification')->nullable()->after('aadhaar_card'); // file path
            $table->string('salary_slip')->nullable()->after('last_qualification'); // file path
            $table->string('previous_experience_letter')->nullable()->after('salary_slip'); // file path
            $table->string('previous_offer_letter')->nullable()->after('previous_experience_letter'); // file path
            $table->string('bank_copy')->nullable()->after('previous_offer_letter'); // file path
            $table->decimal('per_month_salary', 10, 2)->nullable()->after('bank_copy');
            $table->decimal('per_day_salary', 10, 2)->nullable()->after('per_month_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'unique_id',
                'joining_date',
                'dob',
                'pan_card',
                'aadhaar_card',
                'last_qualification',
                'salary_slip',
                'previous_experience_letter',
                'previous_offer_letter',
                'bank_copy',
                'per_month_salary',
                'per_day_salary',
            ]);
        });
    }
};
