<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('attendees', function (Blueprint $table) {
			$table->id();
			$table->foreignId('event_id')->constrained('events')->onDelete('cascade');
			$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
			$table->string('status')->default('registered');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('attendees');
	}
};
