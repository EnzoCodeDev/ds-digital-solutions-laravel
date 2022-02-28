<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataMasterParametrizationBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_master_parametrization_bodies', function (Blueprint $table) {
            $table->id();
            $table->integer('id_header')->unsigned();
            $table->integer('number_card');
            $table->string('title_card');
            $table->integer('version');
            $table->string('select_value');
            $table->string('text_description', 2500)->nullable();
            $table->string('columns')->nullable();
            $table->string('row')->nullable();
            $table->string('title_columns', 5000)->nullable();
            $table->foreign('id_header')->references('id')->on('data_master_parametrization_heads')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('data_master_parametrization_bodies');
    }
}
