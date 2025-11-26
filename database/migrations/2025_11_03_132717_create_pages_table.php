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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('temp', 255)->nullable()->default('default');
            $table->string('page_title', 255);
            $table->string('slug', 300);
            $table->string('short_des', 255);
            $table->longText('page_description');
            $table->string('f_image', 255);
            $table->string('meta_title', 255);
            $table->string('meta_description', 255);
            $table->string('meta_keyword', 255);
            $table->enum('status', ['Active', 'Inactive']);
            $table->foreignId('createdBy')->constrained('users');
            $table->foreignId('updatedBy')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
