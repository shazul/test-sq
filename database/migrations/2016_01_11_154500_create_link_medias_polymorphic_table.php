<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkMediasPolymorphicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id')->unsigned()->index();
            $table->morphs('linkable');
            $table->timestamps();

            $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade');

            $table->unique(['linkable_id', 'linkable_type', 'media_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('link_medias');
    }
}
