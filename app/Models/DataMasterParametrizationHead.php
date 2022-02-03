<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DataMasterParametrizationBody;
use App\Models\DocumentMaster;
use App\Models\TableStateDocument;

class DataMasterParametrizationHead extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'state_document',
        'uuid',
        'version',
        'code',
        'format',
        'template',
        'description',
        'process_select',
        'sub_process_select',
        'position_data_basic',
        'logo_header',
        'position',
        'data_basic',
        'option'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function  dataMasterParametrizationBody()
    {
        return $this->belongsTo(DataMasterParametrizationBody::class);
    }
    public function  documentMasterCardInfo()
    {
        return $this->belongsTo(DocumentMaster::class);
    }
    public function stateDocument(){
        return $this->belongsTo(TableStateDocument::class, 'state_document');
    }
}
