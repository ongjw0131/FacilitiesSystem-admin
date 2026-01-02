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
        Schema::create('event_society', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned();
            $table->bigInteger('society_id')->unsigned();
            $table->timestamps();
            
            // Unique constraint on event_id and society_id combination
            $table->unique(['event_id', 'society_id'], 'uniq_event_society');
            
            // Optional: Add foreign key constraints if you want referential integrity
            // $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            // $table->foreign('society_id')->references('id')->on('societies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_society');
    }
};