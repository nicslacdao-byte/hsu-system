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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to the User
        $table->string('appointment_type'); // 'Freshmen' or 'COE/OJT'
        $table->date('appointment_date');   // YYYY-MM-DD
        $table->string('time_slot');        // e.g., "8:00 AM - 9:00 AM"
        $table->string('status')->default('Pending'); // Pending, Approved, Completed
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
