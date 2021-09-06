<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollabaratorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collabarator', function (Blueprint $table) {
            $table->increments('id');
            $table->string('collab_mails')->nullable();
            $table->integer('note_id')->unsigned();
            $table->foreign('note_id')->references('id')->on('notes');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collabarator');
    }
}
