<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStatusFlow extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_header_document',
        'id_state_document',
        'observacion',
    ];
}
