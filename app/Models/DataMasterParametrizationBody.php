<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentMaster;
use App\Models\DataMasterParametrizationHead;
use App\Models\DataMasterParametrizationBodyTable;

class DataMasterParametrizationBody extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_header',
        'version',
        'number_card',
        'title_card',
        'select_value',
        'text_description',
        'image',
        'link',
        'link_description',
        'file',
        'file_description',
        'list',
        'celda_select',
        'columns',
        'row',
        'identity_data_position',
        'type_celda',
        'title_columns',
        'list_value_celda',
        'card_info_table',
    ];
    public function  dataMasterParametrizationBody()
    {
        return $this->belongsTo(DataMasterParametrizationHead::class);
    }
    public function  dataMasterParametrizationBodyTable()
    {
        return $this->belongsTo(DataMasterParametrizationBodyTable::class);
    }
    public function  documentMasterCardInfo()
    {
        return $this->belongsTo(DocumentMaster::class);
    }
}
