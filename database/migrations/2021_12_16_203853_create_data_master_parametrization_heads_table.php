<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataMasterParametrizationHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_master_parametrization_heads', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('state_document');
            $table->string('uuid');
            $table->integer('version');
            $table->string('code', 200);
            $table->string('format', 400);
            $table->string('template', 100);
            $table->string('description', 5000);
            $table->string('logo_header')->nullable();
            $table->string('position');
            $table->string('position_data_basic')->nullable();
            $table->string('process_select', 1000);
            $table->string('sub_process_select', 1000);
            $table->string('data_basic',50000)->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('state_document')->references('id')->on('table_state_documents')
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
        Schema::dropIfExists('data_master_parametrization_heads');
    }
}
