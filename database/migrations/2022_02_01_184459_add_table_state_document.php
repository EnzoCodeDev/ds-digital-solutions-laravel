<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TableStateDocument;
class AddTableStateDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createStateDocument = new TableStateDocument;
        $createStateDocument->state_document = 'Nuevo';
        $createStateDocument->save();
        $createStateDocument = new TableStateDocument;
        $createStateDocument->state_document = 'Revision';
        $createStateDocument->save();
        $createStateDocument = new TableStateDocument;
        $createStateDocument->state_document = 'Aprovado';
        $createStateDocument->save();
        $createStateDocument = new TableStateDocument;
        $createStateDocument->state_document = 'Rechazado';
        $createStateDocument->save();
        $createStateDocument = new TableStateDocument;
        $createStateDocument->state_document = 'Borrador';
        $createStateDocument->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
