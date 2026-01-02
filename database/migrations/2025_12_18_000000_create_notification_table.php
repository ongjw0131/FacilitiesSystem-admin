<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->id('notificationID');
            
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('societyID');
            $table->unsignedBigInteger('postID')->nullable();
            
            $table->string('type'); // 'post_created', 'comment_added', etc.
            $table->string('title');
            $table->text('message');
            
            $table->boolean('isRead')->default(false);
            $table->timestamp('readAt')->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('userID')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('societyID')
                  ->references('societyID')->on('society')
                  ->onDelete('cascade');
                  
            $table->foreign('postID')
                  ->references('postID')->on('post')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
