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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sport');
            $table->string('image')->nullable();
            $table->string('country')->nullable();
            $table->string('asian_ranking')->nullable();
            $table->string('national_ranking')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('hometown')->nullable();
            $table->string('slug')->unique();
            $table->tinyInteger('status')->default(1); // 0=inactive, 1=active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
