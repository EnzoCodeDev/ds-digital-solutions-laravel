<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMasterParametrizationTable extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_header',
        'id_card',
        'type_celda',
        'type_lista',
        'index_table',
    ];
}
