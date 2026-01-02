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
        Schema::table('society_user', function (Blueprint $table) {
            // Make position nullable for pending join requests
            $table->string('position')->nullable()->change();
            
            // Update status enum to include 'pending' and 'declined'
            $table->enum('status', ['active', 'kicked', 'left', 'banned', 'pending', 'declined'])
                  ->default('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('society_user', function (Blueprint $table) {
            // Revert position back to not nullable
            $table->string('position')->change();
            
            // Revert status enum back to original values
            $table->enum('status', ['active', 'kicked', 'left', 'banned'])
                  ->default('active')->change();
        });
    }
};
