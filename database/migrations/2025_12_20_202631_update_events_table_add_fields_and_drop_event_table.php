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
        // ✅ Alter events table
        Schema::table('events', function (Blueprint $table) {
            $table->string('location')->after('end_date');
            $table->integer('capacity')->nullable()->after('location');
            $table->enum('entry_type', ['FREE', 'TICKETED'])->after('status');
            $table->boolean('is_deleted')->default(0)->after('entry_type');
            $table->string('image_url_path')->nullable()->after('is_deleted');
        });

        // ✅ Drop wrong table "event"
        Schema::dropIfExists('event');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ❌ Remove added columns
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'capacity',
                'entry_type',
                'is_deleted',
                'image_url_path',
            ]);
        });

        // 🔁 Recreate "event" table if rollback
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
