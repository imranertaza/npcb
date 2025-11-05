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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_title', 155)->charset('latin1')->collation('latin1_swedish_ci');
            $table->text('slug')->charset('latin1')->collation('latin1_swedish_ci');
            $table->string('short_des', 155)->charset('utf8mb3')->collation('utf8mb3_unicode_ci');
            $table->longText('description')->charset('utf8mb3')->collation('utf8mb3_unicode_ci');
            $table->text('meta_title')->nullable()->charset('latin1')->collation('latin1_swedish_ci');
            $table->text('meta_keyword')->nullable()->charset('latin1')->collation('latin1_swedish_ci');
            $table->text('meta_description')->nullable()->charset('utf8mb3')->collation('utf8mb3_unicode_ci');
            $table->string('image', 255)->charset('latin1')->collation('latin1_swedish_ci');
            $table->string('alt_name', 255)->nullable()->charset('latin1')->collation('latin1_swedish_ci');
            $table->string('video_id', 255)->nullable()->charset('latin1')->collation('latin1_swedish_ci');
            $table->timestamp('publish_date')->useCurrent();
            $table->enum('status', ['0', '1'])->default('1')->charset('latin1')->collation('latin1_swedish_ci');
            $table->unsignedBigInteger('createdBy');
            $table->unsignedBigInteger('updatedBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
