<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Correo extends Model
{
    protected $table = 'correo';

    protected $primaryKey = 'id_correo';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'id_plantilla',
        'id_token_participante',
        'fecha_envio',
        'numero_recordatorio',
        'estado',
    ];

    protected $casts = [
        'id_plantilla' => 'integer',
        'id_token_participante' => 'integer',
        'fecha_envio' => 'datetime',
        'numero_recordatorio' => 'integer',
    ];

    public function plantilla(): BelongsTo
    {
        return $this->belongsTo(Plantilla::class, 'id_plantilla', 'id_plantilla');
    }

    public function tokenParticipante(): BelongsTo
    {
        return $this->belongsTo(TokenParticipante::class, 'id_token_participante', 'id_token_participante');
    }
}
