<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plantilla extends Model
{
    protected $table = 'plantilla';
    protected $primaryKey = 'id_plantilla';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'tipo',
        'asunto',
        'cuerpo',
    ];
    
    public function correos(): HasMany
    {
        return $this->hasMany(Correo::class, 'id_plantilla', 'id_plantilla');
    }
}