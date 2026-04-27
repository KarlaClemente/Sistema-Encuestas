<?php

namespace App\Http\Services;

use App\Http\DTOs\in\DtoEncuestaIn;
use App\Http\DTOs\out\DtoEncuestaOut;
use App\Http\DTOs\out\DtoPreguntaOut;
use App\Models\Encuesta;
use Illuminate\Support\Facades\DB;

class SvcEncuesta
{
    public function __construct(
        private SvcPregunta $svcPregunta,
        private SvcGrupo $svcGrupo,
        private SvcTokenParticipante $svcTokenParticipante,
        private SvcTokenEncuesta $svcTokenEncuesta,
    ) {}

    
    public function store(DtoEncuestaIn $in): DtoEncuestaOut
    {
        $encuesta = DtoEncuestaOut::fromModel(Encuesta::create($in->toArray()));

        return $encuesta;
    }

    public function storeTokens(int $idEncuesta): void
    {
        $encuesta = Encuesta::findOrFail($idEncuesta);
        $participantes = $this->svcGrupo->participantes($encuesta->id_grupo);
        foreach ($participantes as $participante) {
            $tokenParticipante = $this->svcTokenParticipante->store($participante->idParticipante);
            $this->svcTokenEncuesta->store($idEncuesta, $tokenParticipante->idTokenParticipante);
        }
    }

    public function deleteEncuesta(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $encuesta = Encuesta::findOrFail($id);
            $preguntas = $this->getPreguntas($id);
            foreach ($preguntas as $pregunta) {
                $this->svcPregunta->delete($pregunta->idPregunta);
            }

            return $encuesta->delete();
        });
    }

    public function deleteTokensEncuesta(int $id): array
    {
        $encuesta = Encuesta::findOrFail($id);
        $tokensEncuesta = $encuesta->tokensEncuesta;
        $idTokensParticipantes = [];
        foreach ($tokensEncuesta as $tokenEncuesta) {
            $idTokensParticipantes[] = $tokenEncuesta->id_token_participante;
            $this->svcTokenEncuesta->delete($tokenEncuesta->id_token_encuesta);
        }

        return $idTokensParticipantes;
    }

    public function index(?string $buscar = null): array
    {
        $encuestas = Encuesta::whereNull('id_plantilla')
            ->with('tipoEncuesta')
            ->get();

        return $encuestas->map(fn ($encuesta) => DtoEncuestaOut::fromModelWithoutPreguntas($encuesta)
        )->toArray();
    }

    public function show(int $id): DtoEncuestaOut
    {
        $encuesta = Encuesta::with(['preguntas' => function ($q) {
            $q->orderBy('orden');
        }])->findOrFail($id);

        return DtoEncuestaOut::fromModel($encuesta);
    }

    public function update(DtoEncuestaIn $in, int $id): DtoEncuestaOut
    {
        $encuesta = Encuesta::findOrFail($id);
        $encuesta->update($in->toArray());

        return DtoEncuestaOut::fromModel($encuesta->fresh());
    }

    public function getPreguntas(int $id): array
    {
        $encuesta = Encuesta::with(['preguntas' => fn ($q) => $q->orderBy('orden')])
            ->findOrFail($id);

        return $encuesta->preguntas
            ->map(fn ($p) => DtoPreguntaOut::fromModel($p))
            ->toArray();
    }

    /**
     * Se obtienen todas las encuestas que no provienen de una plantilla a partir de un titulo y si estan completados
     * @param string $titulo El titulo de la encuesta a buscar
     * @param bool $estaCompletado True en caso de que solo se busquen las encuestas que estan comlpetadas, False en caso de buscar las encuestas que estan en progreso
     * @return DtoEncuestaOut[] Arreglo con los DTO's de las encuestas que cumplan con los filtros proporcionados
     */
    public function search(?string $titulo=null, bool $estaCompletado=false): array
    {
        $encuestas = Encuesta::whereNull('id_plantilla');
        if ($titulo !== null && $titulo !== '') {
            $encuestas->where('titulo', 'like', '%'.$titulo.'%');
        }
        if ($estaCompletado) {
            $encuestas->where('completada', true);
        }
        $encuestas = $encuestas->get();
        return $encuestas->map(fn($encuesta) => DtoEncuestaOut::fromModelWithoutPreguntas($encuesta))
                         ->toArray();
    }
}
