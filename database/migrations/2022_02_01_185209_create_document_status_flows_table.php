<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentStatusFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_status_flows', function (Blueprint $table) {
            $table->id();
            $table->integer('id_header_document')->unsigned();
            $table->integer('id_state_document')->unsigned();
            $table->string('observacion')->nullable();
            $table->foreign('id_header_document')->references('id')->on('data_master_parametrization_heads')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_state_document')->references('id')->on('table_state_documents')
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
        Schema::dropIfExists('document_status_flows');
    }
}
