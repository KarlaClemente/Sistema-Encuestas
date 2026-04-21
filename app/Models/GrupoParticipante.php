<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupoParticipante extends Model
{
    protected $table = 'grupo_participante';

    protected $primaryKey = ['id_grupo', 'id_participante'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id_grupo',
        'id_participante',
    ];

    protected $casts = [
        'id_grupo' => 'integer',
        'id_participante' => 'integer',
    ];

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    public function participante(): BelongsTo
    {
        return $this->belongsTo(Participante::class, 'id_participante', 'id_participante');
    }
}
