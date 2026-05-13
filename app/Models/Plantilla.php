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
        'id_encuesta',
        'id_encuesta_plantilla',
    ];
    protected $casts =[
        'id_encuesta' => 'integer',
        'id_encuesta_plantilla' => 'integer',
    ];

    public function correos(): HasMany
    {
        return $this->hasMany(Correo::class, 'id_plantilla', 'id_plantilla');
    }

    public function encuesta(): BelongsTo
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta', 'id_encuesta');
    }
    public function encuestaPlantilla(): BelongsTo
    {
        return $this->belongsTo(EncuestaPlantilla::class, 'id_encuesta_plantilla', 'id_encuesta_plantilla');
    }
}