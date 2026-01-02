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
        Schema::table('society', function (Blueprint $table) {
            if (!Schema::hasColumn('society', 'whoCanPost')) {
                $table->enum('whoCanPost', ['president_only', 'president_and_committee'])
                      ->default('president_only')
                      ->after('joinType');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('society', function (Blueprint $table) {
            if (Schema::hasColumn('society', 'whoCanPost')) {
                $table->dropColumn('whoCanPost');
            }
        });
    }
};
