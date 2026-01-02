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
        /*
        |--------------------------------------------------------------------------
        | File
        |--------------------------------------------------------------------------
        */
        Schema::create('file', function (Blueprint $table) {
            $table->id('fileID');
            $table->string('filePath');
            $table->string('originalName')->nullable();
            $table->bigInteger('fileSize')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | File_Post (Pivot)
        |--------------------------------------------------------------------------
        */
        Schema::create('file_post', function (Blueprint $table) {
            $table->id('filePostID');

            $table->unsignedBigInteger('postID');
            $table->unsignedBigInteger('fileID');

            $table->timestamps();

            $table->foreign('postID')
                  ->references('postID')->on('post')
                  ->onDelete('cascade');

            $table->foreign('fileID')
                  ->references('fileID')->on('file')
                  ->onDelete('cascade');

            $table->unique(['postID', 'fileID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_post');
        Schema::dropIfExists('file');
    }
};
