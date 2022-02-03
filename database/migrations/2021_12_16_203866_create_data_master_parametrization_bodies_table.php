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
            $table->string('celda_select', 5000)->nullable();
            $table->string('columns')->nullable();
            $table->string('row')->nullable();
            $table->string('identity_data_position', 5000)->nullable();
            $table->string('type_celda', 5000)->nullable();
            $table->string('title_columns', 5000)->nullable();
            $table->string('list_value_celda', 5000)->nullable();
            $table->string('card_info_table', 50000)->nullable();
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
