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
        if (!Schema::hasTable('event_tickets')) {
            Schema::create('event_tickets', function (Blueprint $table) {
                $table->id(); // ticketID
                $table->unsignedBigInteger('event_id');

                $table->string('ticket_name'); // Early Bird / Normal
                $table->decimal('price', 10, 2)->unsigned();

                $table->integer('total_quantity')->unsigned();
                $table->integer('sold_quantity')->unsigned()->default(0);

                $table->dateTime('sales_start_at');
                $table->dateTime('sales_end_at');

                $table->enum('status', [
                    'draft',
                    'active',
                    'paused',
                    'sold_out',
                    'expired'
                ])->default('draft');

                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->foreign('event_id')
                    ->references('id')
                    ->on('events')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
