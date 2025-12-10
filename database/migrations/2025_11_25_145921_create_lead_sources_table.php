<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lead_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. 'WhatsApp','Facebook','Email','Manual','Excel Upload'
            $table->text('meta')->nullable();
            $table->timestamps();
        });

        // seed typical sources
        DB::table('lead_sources')->insert([
            ['name' => 'WhatsApp', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Facebook', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Email', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manual', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Excel Upload', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('lead_sources');
    }
};
