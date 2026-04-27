<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pregunta extends Model
{
    protected $table = 'pregunta';
    protected $primaryKey = 'id_pregunta';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id_encuesta',
        'id_encuesta_plantilla',
        'id_tipo_pregunta',
        'texto',
        'orden',
        'min_seleccion',
        'max_seleccion',
    ];

    protected $casts = [
        'id_encuesta'=> 'integer',
        'id_encuesta_plantilla' => 'integer',
        'id_tipo_pregunta' => 'integer',
        'orden' => 'integer',
        'min_seleccion' => 'integer',
        'max_seleccion' => 'integer',
    ];

    public function tipoPregunta(): BelongsTo
    {
        return $this->belongsTo(TipoPregunta::class, 'id_tipo_pregunta', 'id_tipo_pregunta');
    }

    public function opcionesPregunta(): HasMany
    {
        return $this->hasMany(OpcionPregunta::class, 'id_pregunta', 'id_pregunta');
    }

    public function filasMatriz(): HasMany
    {
        return $this->hasMany(FilaMatriz::class, 'id_pregunta', 'id_pregunta');
    }

    public function columnasMatriz(): HasMany
    {
        return $this->hasMany(ColumnaMatriz::class, 'id_pregunta', 'id_pregunta');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'id_pregunta', 'id_pregunta');
    }

    public function encuestaPlantilla(): BelongsTo
    {
        return $this->belongsTo(EncuestaPlantilla::class, 'id_encuesta_plantilla', 'id_encuesta_plantilla');
    }

    public function encuesta(): BelongsTo
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta', 'id_encuesta');
    }
}