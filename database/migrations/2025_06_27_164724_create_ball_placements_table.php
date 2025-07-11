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
        Schema::create('ball_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bucket_id')->constrained()->onDelete('cascade');
            $table->foreignId('ball_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->string('session_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ball_placements');
    }
};
