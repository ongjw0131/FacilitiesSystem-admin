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
        if (!Schema::hasTable('ticket_orders')) {
            Schema::create('ticket_orders', function (Blueprint $table) {
                $table->id(); // ticketOrderID
                $table->unsignedBigInteger('ticket_id');
                $table->unsignedBigInteger('user_id');

                $table->integer('quantity');
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total_amount', 10, 2);

                $table->enum('status', [
                    'pending',
                    'paid',
                    'cancelled',
                    'expired'
                ])->default('pending');

                $table->timestamp('ordered_at')->useCurrent();
                $table->dateTime('expired_at')->nullable();

                $table->string('cancel_reason')->nullable();
                $table->softDeletes();

                $table->timestamps();

                $table->foreign('ticket_id')
                    ->references('id')
                    ->on('event_tickets')
                    ->onDelete('cascade');

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');


                $table->unique(['ticket_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_orders');
    }
};
