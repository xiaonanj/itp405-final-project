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
        Schema::create('score_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained()->onDelete('cascade');
            $table->integer('end_number');
            $table->integer('arrow1_score');
            $table->integer('arrow2_score');
            $table->integer('arrow3_score');
            $table->integer('arrow4_score')->nullable();
            $table->integer('arrow5_score')->nullable();
            $table->integer('arrow6_score')->nullable();
            $table->timestamps();
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
