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
    Schema::table('score_entries', function (Blueprint $table) {
        $table->string('arrow1_score', 2)->change();
        $table->string('arrow2_score', 2)->change();
        $table->string('arrow3_score', 2)->change();
        $table->string('arrow4_score', 2)->nullable()->change();
        $table->string('arrow5_score', 2)->nullable()->change();
        $table->string('arrow6_score', 2)->nullable()->change();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_entries');
    }
};
