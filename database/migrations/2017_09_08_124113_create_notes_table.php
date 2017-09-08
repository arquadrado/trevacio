<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reading_session_id')->unsigned();
            $table->string('title');
            $table->text('body');
            $table->timestamps();

            $table->foreign('reading_session_id')->references('id')->on('reading_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notes', function (Blueprint $table) {
            $table->dropForeign('notes_reading_session_id_foreign');
        });
    }
}
