<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoPregunta extends Model
{
    protected $table = 'tipo_pregunta';

    protected $primaryKey = 'id_tipo_pregunta';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'nombre',
    ];

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class, 'id_tipo_pregunta', 'id_tipo_pregunta');
    }
}
