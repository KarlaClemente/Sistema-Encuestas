<?php

namespace App\Http\Controllers;

use App\Http\Services\SvcEncuesta;
use App\Http\Services\SvcPlantilla;
use App\Http\DTOs\DtoPlantilla;
use App\Http\Requests\PlantillaRequest;
use Illuminate\Http\Request;

class PlantillaController extends Controller
{
    public function __construct(
        private SvcEncuesta $svcEncuesta,
        private SvcPlantilla $svcPlantilla,
    ) {}

    public function index(Request $request, int $encuestaId)
    {
        try {
            $esEncuesta = $request->query('esEncuesta', true);
            $mostrarBarraProgreso = $request->query('mostrarBarraProgreso', false);

            $encuesta = $this->svcEncuesta->showWithoutPreguntas($encuestaId);
            $this->svcEncuesta->validateEsEditable($encuesta);
            $plantillas = $this->svcPlantilla->getPlantillasByIdEncuesta($encuesta->id);
            
            return view('layouts.app.Plantilla.form-correo', ['plantillas' => $plantillas,
                                                            'encuesta' => $encuesta,
                                                            'esEncuesta' => $esEncuesta,
                                                            'mostrarBarraProgreso' => $mostrarBarraProgreso]);
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo visualizar los correo: ' . $e->getMessage());
        }
    }

    public function update(PlantillaRequest $request, int $id)
    {
        try {
            $dto = DtoPlantilla::fromRequest($request);
            $this->svcPlantilla->update($dto, $id);

            return back()->with('success', 'Correo modificado');
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo modificar el correo: ' . $e->getMessage());
        }
    }
}
