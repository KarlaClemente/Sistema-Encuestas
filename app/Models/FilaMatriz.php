<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FilaMatriz extends Model
{
    protected $table = 'fila_matriz';
    protected $primaryKey = 'id_fila_matriz';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'id_pregunta',
        'texto',
        'orden',
    ];

    protected $casts = [
        'id_pregunta' => 'integer',
        'orden' => 'integer',
    ];

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta', 'id_pregunta');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'id_fila_matriz', 'id_fila_matriz');
    }
}