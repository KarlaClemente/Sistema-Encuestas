<?php

namespace App\Http\Controllers;

use App\Http\DTOs\in\DtoPreguntaIn;
use App\Http\Requests\PreguntaRequest;
use App\Http\Services\SvcPregunta;

class PreguntaController extends Controller
{
    public function __construct(
        private SvcPregunta $svc
    ) {}

    public function index()
    {
        $encuestas = $this->svcEncuesta->index();
        // $plantillas = $this->svcEncuestaPlantilla->index();

        return view('layouts.app.home', [
            'encuestas' => $encuestas,
            //    'plantillas' => $plantillas
        ]);
    }

    public function store(PreguntaRequest $request)
    {
        try {
            $dto = DtoPreguntaIn::fromRequest($request);
            $pregunta = $this->svc->store($dto);

            return back()->with('success', 'Pregunta creada');
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo crear la pregunta: '.$e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->svc->delete($id);

            return back()->with('success', 'Pregunta eliminada');
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo eliminar la pregunta: '.$e->getMessage());
        }
    }

    public function update(PreguntaRequest $request, int $id)
    {
        try {
            $dto = DtoPreguntaIn::fromRequest($request);
            $this->svc->update($dto, $id);

            return back()->with('success', 'Pregunta modificada');
        } catch (\Exception $e) {
            return back()->withErrors('No se pudo modificar la pregunta: '.$e->getMessage());
        }
    }
}
