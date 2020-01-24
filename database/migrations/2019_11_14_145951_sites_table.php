<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('network_id')->index();
            $table->string('name');
            $table->string('domain');
            $table->text('analytics')->nullable()->comment('GA Property/View ID, pageViews, etc...');
            $table->text('footer')->nullable()->comment('About Us, Editorial, Management, etc...');
            $table->text('meta')->nullable()->comment('Title, Description, Keywords, etc...');

            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('network_id')->references('id')->on('networks')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sites');
    }
}
