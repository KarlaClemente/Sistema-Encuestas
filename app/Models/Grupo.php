<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $table = 'grupo';
    protected $primaryKey = 'id_grupo';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'nombre',
        'docente',
    ];

    public function gruposParticipante(): HasMany
    {
        return $this->hasMany(GrupoParticipante::class, 'id_grupo', 'id_grupo');
    }

    public function encuestas(): HasMany
    {
        return $this->hasMany(Encuesta::class, 'id_grupo', 'id_grupo');
    }
}