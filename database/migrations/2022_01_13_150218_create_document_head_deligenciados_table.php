<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentHeadDeligenciadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_head_deligenciados', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('user_id');
            $table->integer('id_header_original');
            $table->integer('id_process');
            $table->integer('id_sub_proccess');
            $table->integer('version');
            $table->string('code', 200);
            $table->string('format', 400);
            $table->string('template', 100);
            $table->string('description', 5000);
            $table->string('logo_header')->nullable();
            $table->string('position');
            $table->string('position_data_basic')->nullable();
            $table->string('data_basic',50000)->nullable();
            $table->string('name_user_deligenciar')->nullable();
            $table->string('identify_user_deligenciar')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_header_original')->references('id')->on('data_master_parametrization_heads')
                ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('id_process')->references('id')->on('proceso_models')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_sub_proccess')->references('id')->on('sub_procesos')
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
        Schema::dropIfExists('document_head_deligenciados');
    }
}
