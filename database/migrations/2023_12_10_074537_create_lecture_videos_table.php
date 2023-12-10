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
        Schema::create('lecture_videos', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->text('thumbnail_url');
            $table->float('duration');
            $table->unsignedBigInteger('lecture_id')->unique();

            // Foreign key
            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_videos');
    }
};
