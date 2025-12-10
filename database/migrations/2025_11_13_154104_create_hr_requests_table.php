<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('hr_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');      // requester
            $table->string('type');                     // resignation, complaint, other
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('attachment')->nullable();   // path to file
            $table->enum('status', ['pending','in_review','resolved','rejected'])->default('pending');
            $table->unsignedBigInteger('assigned_to')->nullable(); // HR user handling
            $table->text('hr_response')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hr_requests');
    }
};

 
