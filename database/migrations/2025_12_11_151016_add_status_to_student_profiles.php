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
    Schema::table('student_profiles', function (Blueprint $table) {
        // Adding the new fields for Staff use
        $table->string('medical_status')->nullable()->default('Pending'); // Options: Complete / Incomplete / Pending
        $table->date('date_checked')->nullable();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            //
        });
    }
};
