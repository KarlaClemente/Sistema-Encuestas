<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Http\DTOs\in\DtoEncuestaIn;
use App\Http\DTOs\out\DtoEncuestaOut;
use App\Http\DTOs\out\DtoPreguntaOut;
use App\Http\DTOs\DtoPlantilla;
use App\Http\DTOs\in\DtoCorreoIn;
use App\Models\Encuesta;
use Illuminate\Support\Facades\DB;

class SvcEncuesta
{
    public function __construct(
        private SvcPregunta $svcPregunta,
        private SvcGrupo $svcGrupo,
        private SvcTokenParticipante $svcTokenParticipante,
        private SvcTokenEncuesta $svcTokenEncuesta,
        private SvcPlantilla $svcPlantilla,
        private SvcCorreo $svcCorreo,
    ) {}

    public function getEncuestasEnProgreso(): array
    {
        $now = Carbon::now();
        return Encuesta::with(['encuestaPlantilla', 'grupo'])
                ->where('fecha_inicio', '<=', $now)
                ->where('fecha_termino', '>=', $now)
                ->where('completada', false)
                ->get()
                ->map(fn($encuesta) => DtoEncuestaOut::fromModelWithoutPreguntas($encuesta)); 
    }
    
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
            $tokenParticipante = $this->svcTokenParticipante->store($participante);
            $this->svcTokenEncuesta->store($idEncuesta, $tokenParticipante->idTokenParticipante);
        }
    }

    public function storeCorreos(int $idEncuesta, string $tipoPlantilla, Carbon $fechaEnvio, int $numeroRecordatorio): void
    {
        $encuesta = Encuesta::findOrFail($idEncuesta);
        $plantilla = $this->svcPlantilla->getPlantillaByTipoByIdEncuesta($encuesta->id_encuesta, $tipoPlantilla);
        $tokensEncuesta = $this->svcTokenEncuesta->getByIdEncuestaByCompletado($idEncuesta, False);
        foreach ($tokensEncuesta as $tokenParticipante) {
            $dtoCorreo  = DtoCorreoIn::crearParaEnvio($plantilla, $tokenParticipante->idTokenParticipante, $fechaEnvio, $numeroRecordatorio);
            $this->svcCorreo->store($dtoCorreo);
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

    public function deletePlantillas(int $id)
    {
        $encuesta = Encuesta::with('plantillas.correos')->findOrFail($id);
        $plantillas = $encuesta->plantillas;
        foreach ($plantillas as $plantilla) {
            $correos = $plantilla->correos;
            foreach ($correos as $correo) {
                $this->svcCorreo->delete($correo->id_correo);
            }
            $this->svcPlantilla->delete($plantilla->id_plantilla);
        }
    }

    public function index(?string $buscar = null): array
    {
        $encuestas = Encuesta::whereNull('id_plantilla')
            ->with(['tipoEncuesta', 'grupo'])
            ->get();

        return $encuestas->map(fn ($encuesta) => DtoEncuestaOut::fromModelWithoutPreguntas($encuesta)
        )->toArray();
    }

    public function show(int $id): DtoEncuestaOut
    {
        $encuesta = Encuesta::with(['grupo', 'preguntas' => function ($q) {
            $q->orderBy('orden');
        }, 'preguntas.opcionesPregunta', 'preguntas.filasMatriz', 'preguntas.columnasMatriz',])
        ->findOrFail($id);

        return DtoEncuestaOut::fromModel($encuesta);
    }

    public function showWithoutPreguntas(int $id): DtoEncuestaOut
    {
        $encuesta = Encuesta::with('grupo')->findOrFail($id);
        
        return DtoEncuestaOut::fromModelWithoutPreguntas($encuesta);
    }

    public function update(DtoEncuestaIn $in, int $id): DtoEncuestaOut
    {
        $encuesta = Encuesta::findOrFail($id);
        $encuesta->update($in->toUpdateArray());

        $encuesta = Encuesta::with('grupo')->findOrFail($id);
        return DtoEncuestaOut::fromModelWithoutPreguntas($encuesta);
    }

    public function updateFechaEnvioInvitaciones(int $id, Carbon $fechaEnvio){
        $plantilla = $this->svcPlantilla->getPlantillaByTipoByIdEncuesta($id, 'invitacion');
        $correos = $this->svcPlantilla->getCorreos($plantilla->idPlantilla);
        foreach ($correos as $correo) {
            $dto = new DtoCorreoIn(
                $correo->id,
                $plantilla,
                $correo->idTokenParticipante,
                $fechaEnvio,
                $correo->numeroRecordatorio,
                $correo->estado
            );
            $this->svcCorreo->update($dto);
        }
    }

    public function getPreguntas(int $id): array
    {
        $encuesta = Encuesta::with(['preguntas' => fn ($q) => $q->orderBy('orden'), 'preguntas.opcionesPregunta', 'preguntas.filasMatriz', 'preguntas.columnasMatriz',])
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

    public function concluirEncuestas(): void
    {
        $now = Carbon::now();
        $encuestas = Encuesta::where('fecha_termino', '<=', $now)
            ->where('completada', false)
            ->get();
        foreach ($encuestas as $encuesta) {
            $encuesta->completada = true;
            $encuesta->save();
        }
    }

    public function validateFechas(DtoEncuestaIn $dto)
    {
        $now = Carbon::now();
        if ($dto->fechaInicio->isBefore($now)) {
            throw new \Exception('La fecha de inicio no puede ser anterior a la fecha y hora actual');
        }
        if ($dto->fechaTermino->isBefore($dto->fechaInicio)) {
            throw new \Exception('La fecha de término debe ser posterior a la fecha de inicio');
        }
        if ($dto->fechaTermino->isBefore($now)) {
            throw new \Exception('La fecha de término no puede ser anterior a la fecha y hora actual');
        }
    }

    public function validateExistenciaEncuesta(int $id)
    {
        if (Encuesta::findOrFail($id) === null) {
            throw new \Exception('No existe la encuesta');
        }
    }

    public function validateEsEditable(DtoEncuestaOut $encuesta)
    {
        if ($encuesta->completada) {
            throw new \Exception('No se pueden editar encuestas que ya finalizadas');
        }
        if ($encuesta->fechaInicio->isPast()) {
            throw new \Exception('No se pueden editar encuestas que ya iniciaron');
        }
    }
}
