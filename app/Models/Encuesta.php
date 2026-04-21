<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Encuesta extends Model
{
    protected $table = 'encuesta';

    protected $primaryKey = 'id_encuesta';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'id_tipo_encuesta',
        'id_grupo',
        'id_plantilla',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_termino',
        'texto_advertencia',
        'estilo',
        'completada',
    ];

    protected $casts = [
        'id_tipo_encuesta' => 'integer',
        'id_grupo' => 'integer',
        'id_plantilla' => 'integer',
        'fecha_inicio' => 'datetime',
        'fecha_termino' => 'datetime',
        'completada' => 'boolean',
    ];

    public function tipoEncuesta(): BelongsTo
    {
        return $this->belongsTo(TipoEncuesta::class, 'id_tipo_encuesta', 'id_tipo_encuesta');
    }

    public function encuestaPlantilla(): BelongsTo
    {
        return $this->belongsTo(EncuestaPlantilla::class, 'id_plantilla', 'id_encuesta_plantilla');
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class, 'id_encuesta', 'id_encuesta');
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    public function tokensEncuesta(): HasMany
    {
        return $this->hasMany(TokenEncuesta::class, 'id_encuesta', 'id_encuesta');
    }
}
