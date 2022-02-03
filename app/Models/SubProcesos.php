<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProcesoModel;
use App\Models\User;

class SubProcesos extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'uuid',
        'id_process',
        'subProceso',
    ];
    public function proceso()
    {
        return $this->belongsTo(ProcesoModel::class, 'id_process');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
