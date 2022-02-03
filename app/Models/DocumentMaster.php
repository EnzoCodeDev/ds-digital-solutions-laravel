<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DocumentHeadDeligenciado;
use App\Models\DataMasterParametrizationHead;
class DocumentMaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'id_card',
        'id_header',
        'num_version',
        'card_info',
        'type_card',
        'title_card',
        'card_info_table',
        'text_description',
        'text_description_item',
        'link',
        'link_description',
        'file',
        'format_archivo',
        'file_description',
        'img',
        'date',
        'format_img',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function  dataMasterParametrizationHead()
    {
        return $this->belongsTo(DataMasterParametrizationHead::class);
    }
    public function  dataMasterDocumentHeadDeligenciado()
    {
        return $this->belongsTo(DocumentHeadDeligenciado::class);
    }
}
