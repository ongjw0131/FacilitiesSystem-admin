<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Society
        |--------------------------------------------------------------------------
        */
        Schema::create('society', function (Blueprint $table) {
            $table->id('societyID');
            $table->string('societyName');
            $table->text('societyDescription')->nullable();
            $table->string('societyPhotoPath')->nullable();
            $table->timestamp('createDate')->useCurrent();

            $table->enum('joinType', ['open', 'approval', 'closed'])->default('open');
            $table->enum('whoCanPost', ['president_only', 'committee', 'all'])
                  ->default('president_only');

            $table->boolean('isDelete')->default(false);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | SocietyUser (Membership)
        |--------------------------------------------------------------------------
        */
        Schema::create('society_user', function (Blueprint $table) {
            $table->id('societyUserID');

            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('societyID');

            $table->string('position');
            $table->enum('status', ['active', 'kicked', 'left', 'banned'])
                  ->default('active');

            $table->timestamp('joinedDate')->useCurrent();
            $table->timestamp('leftDate')->nullable();

            // Audit fields (NO FK as per your ERD)
            $table->unsignedBigInteger('appointedBy')->nullable();
            $table->unsignedBigInteger('kickedBy')->nullable();
            $table->timestamp('kickedDate')->nullable();

            $table->timestamps();

            // Foreign Keys (core relationships only)
            $table->foreign('societyID')
                  ->references('societyID')->on('society')
                  ->onDelete('cascade');

            $table->foreign('userID')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            // Prevent duplicate membership
            $table->unique(['userID', 'societyID']);
        });

        /*
        |--------------------------------------------------------------------------
        | Post
        |--------------------------------------------------------------------------
        */
        Schema::create('post', function (Blueprint $table) {
            $table->id('postID');

            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('societyID');

            $table->string('title');
            $table->text('content');

            $table->boolean('isDelete')->default(false);
            $table->timestamps();

            $table->foreign('userID')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('societyID')
                  ->references('societyID')->on('society')
                  ->onDelete('cascade');
        });

        /*
        |--------------------------------------------------------------------------
        | Comment
        |--------------------------------------------------------------------------
        */
        Schema::create('comment', function (Blueprint $table) {
            $table->id('commentID');

            $table->unsignedBigInteger('postID');
            $table->unsignedBigInteger('userID');

            $table->text('content');

            $table->timestamps();
            $table->timestamp('deletedAt')->nullable();
            $table->boolean('isDelete')->default(false);

            $table->foreign('postID')
                  ->references('postID')->on('post')
                  ->onDelete('cascade');

            $table->foreign('userID')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */
        Schema::create('image', function (Blueprint $table) {
            $table->id('imageID');
            $table->string('filePath');
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Image_Post (Pivot)
        |--------------------------------------------------------------------------
        */
        Schema::create('image_post', function (Blueprint $table) {
            $table->id('imagePostID');

            $table->unsignedBigInteger('postID');
            $table->unsignedBigInteger('imageID');

            $table->timestamps();

            $table->foreign('postID')
                  ->references('postID')->on('post')
                  ->onDelete('cascade');

            $table->foreign('imageID')
                  ->references('imageID')->on('image')
                  ->onDelete('cascade');

            $table->unique(['postID', 'imageID']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_post');
        Schema::dropIfExists('image');
        Schema::dropIfExists('comment');
        Schema::dropIfExists('post');
        Schema::dropIfExists('society_user');
        Schema::dropIfExists('society');
    }
};
