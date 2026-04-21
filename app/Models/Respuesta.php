<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Respuesta extends Model
{
    protected $table = 'respuesta';

    protected $primaryKey = 'id_respuesta';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'id_token_encuesta',
        'id_pregunta',
        'id_opcion',
        'id_fila_matriz',
        'id_columna_matriz',
        'valor_texto',
    ];

    protected $casts = [
        'id_token_encuesta' => 'integer',
        'id_pregunta' => 'integer',
        'id_opcion' => 'integer',
        'id_fila_matriz' => 'integer',
        'id_columna_matriz' => 'integer',
    ];

    public function tokenEncuesta(): BelongsTo
    {
        return $this->belongsTo(TokenEncuesta::class, 'id_token_encuesta', 'id_token_encuesta');
    }

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta', 'id_pregunta');
    }

    public function opcion(): BelongsTo
    {
        return $this->belongsTo(OpcionPregunta::class, 'id_opcion', 'id_opcion');
    }

    public function filaMatriz(): BelongsTo
    {
        return $this->belongsTo(FilaMatriz::class, 'id_fila_matriz', 'id_fila_matriz');
    }

    public function columnaMatriz(): BelongsTo
    {
        return $this->belongsTo(ColumnaMatriz::class, 'id_columna_matriz', 'id_columna_matriz');
    }
}
