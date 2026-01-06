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
    Schema::create('daily_limits', function (Blueprint $table) {
        $table->id();
        $table->date('date')->unique(); // One rule per specific date
        $table->integer('limit');       // The custom limit (e.g., 250)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_limits');
    }
};
