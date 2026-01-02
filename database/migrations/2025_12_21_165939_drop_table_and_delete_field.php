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
        // Drop attendees table
        Schema::dropIfExists('attendees');

        // Remove entry_type column from events table
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'entry_type')) {
                $table->dropColumn('entry_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate attendees table
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->timestamps();
        });

        // Add entry_type column back to events table
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'entry_type')) {
                $table->string('entry_type')->nullable();
            }
        });
    }
};
