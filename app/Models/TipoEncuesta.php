<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEncuesta extends Model
{
    protected $table = 'tipo_encuesta';

    protected $primaryKey = 'id_tipo_encuesta';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'nombre',
    ];

    public function encuestas(): HasMany
    {
        return $this->hasMany(Encuesta::class, 'id_tipo_encuesta', 'id_tipo_encuesta');
    }

    public function encuestasPlantilla(): HasMany
    {
        return $this->hasMany(EncuestaPlantilla::class, 'id_tipo_encuesta', 'id_tipo_encuesta');
    }
}
