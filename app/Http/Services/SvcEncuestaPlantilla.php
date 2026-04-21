<?php

namespace App\Http\Services;

use App\Models\Encuesta;
use App\Models\EncuestaPlantilla;

class SvcEncuestaPlantilla
{
    public function __construct(
        private SvcEncuesta $svcEncuesta,
        private SvcGrupo $svcGrupo,
    ) {}

    public function index(): array
    {
        $plantillas = EncuestaPlantilla::with('tipoEncuesta')->get();

        return [
            'plantillas' => $plantillas->map(fn ($plantilla) => [
                'id' => $plantilla->id_encuesta_plantilla,
                'titulo' => $plantilla->titulo,
                'descripcion' => $plantilla->descripcion,
                'estilo' => $plantilla->estilo,
                'textoAdvertencia' => $plantilla->texto_advertencia,
                'tipoEncuesta' => $plantilla->tipoEncuesta?->nombre,
            ])->toArray(),
        ];
    }

    public function create(EncuestaRequest $encuesaPlantilla)
    {
        $plantilla = EncuestaPlantilla::create($encuesaPlantilla->toArray());
        foreach ($encuestaPlantilla->grupos as $grupo) {
            $encuesta['id_plantilla'] = $plantilla->idEncuestaPlantilla;
            $encuesta['id_grupo'] = $grupo;
            $encuesta['id_tipo_encuesta'] = $plantilla->idTipoEncuesta;
            Encuesta::create($encuesta);
        }
    }
}
