<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('site_id')->index();
            $table->unsignedInteger('channel_id')->index();
            $table->enum('type', ['articles', 'images', 'videos', 'recipes', 'podcasts', 'pricelists', 'charts', 'pollings'])->index();
            $table->text('headline')->comment('Title, Subtitle, Tags/Keywords, Description, etc...');
            $table->text('editorials')->comment('Is Headline, Editor Choice, Sources, etc...');
            $table->dateTime('published')->useCurrent();
            $table->longText('body');
            $table->text('relates')->nullable()->comment('Related articles');
            $table->text('media')->comment('Gallery (Image, Video), etc...');
            $table->text('reporter')->nullable()->comment('Name, Profile');
            $table->text('editor')->comment('Name, Profile');
            $table->boolean('commentable')->default(true);
            $table->text('analytics')->nullable()->comment('GA Property/View ID, pageViews, etc...');

            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channels')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('posts');
    }
}
