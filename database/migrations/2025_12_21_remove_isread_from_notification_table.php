<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->dropColumn(['isRead', 'readAt']);
        });
    }

    public function down(): void
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->boolean('isRead')->default(false);
            $table->timestamp('readAt')->nullable();
        });
    }
};
