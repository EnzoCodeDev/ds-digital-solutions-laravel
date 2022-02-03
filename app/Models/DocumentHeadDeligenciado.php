<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DataMasterParametrizationBody;
use App\Models\DocumentMaster;
use App\Models\DocumenMasterTable;
use App\Models\ProcesoModel;
use App\Models\SubProcesos;

class DocumentHeadDeligenciado extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'code',
        'user_id',
        'id_process',
        'id_sub_proccess',
        'version',
        'format',
        'template',
        'position',
        'data_basic',
        'logo_header',
        'description',
        'id_header_original',
        'position_data_basic',
        'name_user_deligenciar',
        'identify_user_deligenciar',
        'option'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function  dataMasterParametrizationBody()
    {
        return $this->hasMany(DataMasterParametrizationBody::class);
    }
    public function  documentMasterCardInfoRelaciones()
    {
        return $this->hasMany(DocumentMaster::class, 'id_header');
    }
    public function  documentMasterCardInfoTableRelaciones()
    {
        return $this->hasMany(DocumenMasterTable::class, 'id_header');
    }
    public function  procesosRelacionados()
    {
        return $this->belongsTo(ProcesoModel::class, 'id_process');
    }
    public function  subProcesosRelacionados()
    {
        return $this->belongsTo(SubProcesos::class, 'id_sub_proccess');
    }
}
