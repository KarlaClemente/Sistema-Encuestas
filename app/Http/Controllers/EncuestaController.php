<?php

namespace App\Http\Controllers;

use App\Http\DTOs\in\DtoEncuestaIn;
use App\Http\Requests\EncuestaRequest;
use App\Http\Services\SvcEncuesta;
use App\Http\Services\SvcEncuestaPlantilla;
use App\Http\Services\SvcGrupo;
use App\Http\Services\SvcTipoEncuesta;
use App\Http\Services\SvcTipoPregunta;
use App\Http\Services\SvcTokenParticipante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EncuestaController extends Controller
{
    public function __construct(
        private SvcEncuesta $svcEncuesta,
        private SvcEncuestaPlantilla $svcEncuestaPlantilla,
        private SvcTipoPregunta $svcTipoPregunta,
        private SvcTipoEncuesta $svcTipoEncuesta,
        private SvcGrupo $svcGrupo,
        private SvcTokenParticipante $svcTokenParticipante,
        private array $estilos = [['Azul', 'Violeta', 'Esmeralda', 'Calypso']],
    ) {}

    public function index(Request $request)
    {
        $soloCompletadas = $request->boolean('completadas', false);
        $buscar = $request->input('buscar');

        $encuestas = $this->svcEncuesta->index($soloCompletadas, $buscar);
        $plantillas = $this->svcEncuestaPlantilla->index();

        return view('layouts.app.Encuesta.home', [
            'encuestas' => $encuestas,
            'plantillas' => $plantillas['plantillas'],
        ]);
    }

    public function create(int $idGrupo)
    {
        if ($this->svcGrupo->show($idGrupo) === null) {
            return back()->withErrors('No se puede crear una encuesta de un grupo inexistente');
        }
        $tipoEncuesta = $this->svcTipoEncuesta->index();

        return view('layouts.app.Encuesta.form-encuesta', ['estilos' => $this->estilos,
            'tipoEncuesta' => $tipoEncuesta,
            'grupo' => $idGrupo,
            'encuesta' => null]);
    }

    public function store(EncuestaRequest $request)
    {
        try {
            $dto = DtoEncuestaIn::fromRequest($request);
            $this->validateFechas($dto);

            return DB::transaction(function () use ($dto) {
                $encuesta = $this->svcEncuesta->store($dto);
                $this->svcEncuesta->storeTokens($encuesta->id);

                return redirect()->route('form-preguntas-encuesta', ['id' => $encuesta->id])
                    ->with('success', 'Encuesta creada');
            });
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo crear la encuesta: '.$e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->validateExistenciaEncuesta($id);

            return DB::transaction(function () use ($id) {
                $idTokensParticipantes = $this->svcEncuesta->deleteTokensEncuesta($id);
                foreach ($idTokensParticipantes as $id) {
                    $this->svcTokenParticipante->delete($id);
                }
                $this->svcEncuesta->deleteEncuesta($id);

                return redirect()->route('home')
                    ->with('success', 'La encuesta ha sido eliminada');
            });
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo eliminar la encuesta: '.$e->getMessage());
        }
    }

    public function edit(int $id)
    {
        try {
            $this->validateExistenciaEncuesta($id);
            $encuesta = $this->svcEncuesta->show($id);
            $tipoEncuesta = $this->svcTipoEncuesta->index();

            return view('layouts.app.Encuesta.form-encuesta', ['estilos' => $this->estilos,
                'tipoEncuesta' => $tipoEncuesta,
                'encuesta' => $encuesta]);
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo editar la encuesta: '.$e->getMessage());
        }
    }

    public function update(EncuestaRequest $request, int $id)
    {
        try {
            $this->validateExistenciaEncuesta($id);
            $dto = DtoEncuestaIn::fromRequest($request);
            $this->validateFechas($dto);
            $this->svcEncuesta->update($dto, $id);

            return redirect()->route('form-preguntas-encuesta', ['id' => $id])->with('success', 'Encuesta actualizada');
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo actualizar la información de la encuesta: '.$e->getMessage());
        }
    }

    public function editPreguntas(int $id)
    {
        $this->validateExistenciaEncuesta($id);
        $tiposPregunta = $this->svcTipoPregunta->index();
        $encuesta = $this->svcEncuesta->show($id);

        return view('layouts.app.Encuesta.preguntas-encuesta', ['encuesta' => $encuesta,
            'tiposPregunta' => $tiposPregunta,
            'esEncuesta' => true]);
    }

    private function validateFechas(DtoEncuestaIn $dto)
    {
        if ($dto->fechaTermino->isBefore(Carbon::now()->startOfDay())) {
            throw new \Exception('No se pueden tener encuestas que terminen en el pasado');
        }
    }

    private function validateExistenciaEncuesta(int $id)
    {
        if ($this->svcEncuesta->show($id) === null) {
            throw new \Exception('No existe la encuesta');
        }
    }
}
