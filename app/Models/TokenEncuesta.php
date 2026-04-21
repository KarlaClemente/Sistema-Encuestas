<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TokenEncuesta extends Model
{
    protected $table = 'token_encuesta';

    protected $primaryKey = 'id_token_encuesta';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'id_token_participante',
        'id_encuesta',
        'completado',
    ];

    protected $casts = [
        'id_token_participante' => 'integer',
        'id_encuesta' => 'integer',
        'completado' => 'boolean',
    ];

    public function tokenParticipante(): BelongsTo
    {
        return $this->belongsTo(TokenParticipante::class, 'id_token_participante', 'id_token_participante');
    }

    public function encuesta(): BelongsTo
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta', 'id_encuesta');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'id_token_encuesta', 'id_token_encuesta');
    }
}
