<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumenMasterTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documen_master_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('id_card');
            $table->integer('id_header')->unsigned();
            $table->integer('index_celda');
            $table->string('type_celda')->nullable();
            $table->string('title_celda', 500)->nullable();
            $table->string('text_description', 5000)->nullable();
            $table->string('img')->nullable();
            $table->string('img_extension')->nullable();
            $table->string('link', 1000)->nullable();
            $table->string('link_description', 2000)->nullable();
            $table->string('fecha', 200)->nullable();
            $table->string('lista', 5000)->nullable();
            $table->foreign('id_card')->references('id')->on('data_master_parametrization_bodies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_header')->references('id')->on('document_head_deligenciados')
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
        Schema::dropIfExists('documen_master_tables');
    }
}
