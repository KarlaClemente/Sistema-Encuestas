<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participante extends Model
{
    protected $table = 'participante';

    protected $primaryKey = 'id_participante';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'nombre',
        'email',
    ];

    public function tokensParticipante(): HasMany
    {
        return $this->hasMany(TokenParticipante::class, 'id_participante', 'id_participante');
    }

    public function gruposParticipante(): HasMany
    {
        return $this->hasMany(GrupoParticipante::class, 'id_participante', 'id_participante');
    }
}
