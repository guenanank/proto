<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedSmallInteger('site_id')->index();
            $table->string('name');
            $table->string('slug');
            $table->integer('sub')->index()->nullable();
            $table->boolean('displayed')->default(true);
            $table->smallInteger('sort')->default(0);
            $table->text('analytics')->nullable()->comment('GA Property/View ID, pageViews, etc...');
            $table->text('meta')->nullable()->comment('Title, Description, Keywords, etc...');

            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('channels');
    }
}
