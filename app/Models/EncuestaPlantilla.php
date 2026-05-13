<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncuestaPlantilla extends Model
{
    protected $table = 'encuesta_plantilla';
    protected $primaryKey = 'id_encuesta_plantilla';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id_tipo_encuesta',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_termino',
        'estilo',
        'texto_advertencia',
    ];

    public function tipoEncuesta(): BelongsTo
    {
        return $this->belongsTo(TipoEncuesta::class, 'id_tipo_encuesta', 'id_tipo_encuesta');
    }

    public function encuestas(): HasMany
    {
        return $this->hasMany(Encuesta::class, 'id_plantilla', 'id_encuesta_plantilla');
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class, 'id_encuesta_plantilla', 'id_encuesta_plantilla');
    }

    public function plantillas(): HasMany
    {
        return $this->hasMany(Plantilla::class, 'id_encuesta_plantilla', 'id_encuesta_plantilla');
    }
}