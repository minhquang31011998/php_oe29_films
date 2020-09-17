<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->string('source_key')->comment('ma cua duong dan');
            $table->integer('video_id')->comment('id cua video');
            $table->integer('prioritize')->comment('uu tien');
            $table->integer('status')->default(1)->comment('trang thai');
            $table->integer('movie_id')->comment('id cua movie');
            $table->integer('channel_id')->comment('id cua channel');
            $table->integer('user_id')->comment('nguoi tao');
            $table->timestamps();
            $table->softDeletes()->comment('thoi gian xoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sources');
    }
}
