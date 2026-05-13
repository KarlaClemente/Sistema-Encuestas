<?php

namespace App\Http\Controllers;

use App\Http\DTOs\in\DtoEncuestaIn;
use App\Http\DTOs\out\DtoEncuestaOut;
use App\Http\Requests\EncuestaRequest;
use App\Http\Services\SvcEncuesta;
use App\Http\Services\SvcEncuestaPlantilla;
use App\Http\Services\SvcGrupo;
use App\Http\Services\SvcTipoEncuesta;
use App\Http\Services\SvcTipoPregunta;
use App\Http\Services\SvcTokenParticipante;
use App\Http\Services\SvcPlantilla;
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
        private SvcPlantilla $svcPlantilla,
        private array $estilos = [['Azul', 'Violeta', 'Calypso']],
    ) {}

    public function index()
    {
        $encuestas = $this->svcEncuesta->index();
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
            'encuesta' => null,
            'esEncuesta' => true,
            'mostrarBarraProgreso' => true]);
    }

    public function store(EncuestaRequest $request)
    {
        try {
            $dto = DtoEncuestaIn::fromRequest($request);
            $this->svcEncuesta->validateFechas($dto);

            return DB::transaction(function () use ($dto) {
                $encuesta = $this->svcEncuesta->store($dto);
                $this->svcEncuesta->storeTokens($encuesta->id);
                $plantillas = $this->svcPlantilla->createBasePlantilla($encuesta->id, true);
                $this->svcEncuesta->storeCorreos($encuesta->id, 'invitacion', Carbon::parse($encuesta->fechaInicio), 0);

                return redirect()->route('form-preguntas-encuesta', ['id' => $encuesta->id, 'mostrarBarraProgreso' => true])
                    ->with('success', 'Encuesta creada');
            });
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo crear la encuesta: '.$e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->svcEncuesta->validateExistenciaEncuesta($id);

            return DB::transaction(function () use ($id) {
                $this->svcEncuesta->deletePlantillas($id);
                $idTokensParticipantes = $this->svcEncuesta->deleteTokensEncuesta($id);
                foreach ($idTokensParticipantes as $tokenId) {
                    $this->svcTokenParticipante->delete($tokenId);
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
            $this->svcEncuesta->validateExistenciaEncuesta($id);
            $encuesta = $this->svcEncuesta->show($id);
            $this->svcEncuesta->validateEsEditable($encuesta);
            $tipoEncuesta = $this->svcTipoEncuesta->index();
            $mostrarBarraProgreso = request()->boolean('mostrarBarraProgreso', false);

            return view('layouts.app.Encuesta.form-encuesta', ['estilos' => $this->estilos,
                'tipoEncuesta' => $tipoEncuesta,
                'encuesta' => $encuesta,
                'esEncuesta' => true,
                'mostrarBarraProgreso' => $mostrarBarraProgreso]);
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo editar la encuesta: '.$e->getMessage());
        }
    }

    public function update(EncuestaRequest $request, int $id)
    {
        try {
            $this->svcEncuesta->validateExistenciaEncuesta($id);
            $dto = DtoEncuestaIn::fromRequest($request);
            $this->svcEncuesta->validateFechas($dto);
            DB::transaction(function () use ($dto, $id) {
                $encuesta = $this->svcEncuesta->update($dto, $id);
                $this->svcEncuesta->updateFechaEnvioInvitaciones($encuesta->id, $encuesta->fechaInicio);
            });
            if ($request->boolean('mostrarBarraProgreso')) {
                return redirect()->route('form-preguntas-encuesta', ['id' => $id, 'mostrarBarraProgreso' => true])->with('success', 'Encuesta actualizada');
            }
            return redirect()->route('home')->with('success', 'Encuesta actualizada');
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo actualizar la información de la encuesta: '.$e->getMessage());
        }
    }

    public function editPreguntas(int $id)
    {
        try {
            $this->svcEncuesta->validateExistenciaEncuesta($id);
            $encuesta = $this->svcEncuesta->show($id);
            $this->svcEncuesta->validateEsEditable($encuesta);
            $tiposPregunta = $this->svcTipoPregunta->index();
            $mostrarBarraProgreso = request()->boolean('mostrarBarraProgreso', false);

            return view('layouts.app.Encuesta.preguntas-encuesta', ['encuesta' => $encuesta,
                'tiposPregunta' => $tiposPregunta,
                'esEncuesta' => true,
                'mostrarBarraProgreso' => $mostrarBarraProgreso]);
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo actualizar la información de la encuesta: '.$e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $encuestasABuscar = $request->input('buscar', '');
        $soloCompletadas = $request->boolean('completadas', false);
        $encuestas = $this->svcEncuesta->search($encuestasABuscar, $soloCompletadas);
        return view('layouts.app.Encuesta.home', [
            'encuestas' => $encuestas,
        ]);
    }
}
