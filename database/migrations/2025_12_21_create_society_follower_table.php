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
        Schema::create('society_follower', function (Blueprint $table) {
            $table->id('followerID');
            
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('societyID');
            
            $table->timestamp('followedDate')->useCurrent();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('userID')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('societyID')
                  ->references('societyID')->on('society')
                  ->onDelete('cascade');
            
            // Unique constraint - one follow per user per society
            $table->unique(['userID', 'societyID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('society_follower');
    }
};
