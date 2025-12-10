<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTimeLogsTable extends Migration
{
    public function up()
    {
        Schema::create('task_time_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('duration_seconds')->nullable(); // total run seconds (end - start)
            $table->integer('start_remaining_seconds')->nullable(); // remaining seconds on task when started
            $table->boolean('is_overtime')->default(false);
            $table->integer('overtime_seconds')->default(0);
            $table->text('notes')->nullable();
            $table->text('extra_time_reason')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('extra_time_status', ['Pending', 'Approved', 'Rejected'])
                ->default('Pending');
                   $table->boolean('is_running')->default(0);



            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            // user foreign key (optional)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_time_logs');
    }
}
