<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('id_card')->unsigned();
            $table->integer('id_header')->unsigned();
            $table->integer('num_version');
            $table->string('type_card');
            $table->string('title_card');
            $table->string('text_description', 5000)->nullable();
            $table->string('text_description_item', 5000)->nullable();
            $table->string('link', 500)->nullable();
            $table->string('link_description', 1000)->nullable();
            $table->string('file', 300)->nullable();
            $table->string('format_archivo', 300)->nullable();
            $table->string('img', 300)->nullable();
            $table->string('format_img', 300)->nullable();
            $table->string('date')->nullable();
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
        Schema::dropIfExists('document_masters');
    }
}
