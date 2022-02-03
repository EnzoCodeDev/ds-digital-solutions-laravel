<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumenMasterTable extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_header',
        'id_card',
        'type_celda',
        'index_celda',
        'title_celda',
        'text_description',
        'img',
        'img_extension',
        'link',
        'link_description',
        'fecha',
        'lista',
    ];
}
