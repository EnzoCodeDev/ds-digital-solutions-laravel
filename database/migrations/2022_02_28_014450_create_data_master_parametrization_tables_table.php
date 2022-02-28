<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataMasterParametrizationTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_master_parametrization_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('id_header')->unsigned();
            $table->integer('id_card')->unsigned();
            $table->string('type_celda');
            $table->string('type_lista',5000);
            $table->integer('index_table');
            $table->foreign('id_header')->references('id')->on('data_master_parametrization_heads')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_card')->references('id')->on('data_master_parametrization_bodies')
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
        Schema::dropIfExists('data_master_parametrization_tables');
    }
}
