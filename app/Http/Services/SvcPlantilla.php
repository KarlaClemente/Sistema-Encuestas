<?php

namespace App\Http\Controllers;

use App\Models\Plantilla;

class PlantillaController extends Controller
{
    public function index()
    {
        $plantillas = Plantilla::all();

        return compact('plantillas');
    }

    public function showPorTipo(string $tipo)
    {
        $plantilla = Plantilla::where('tipo', $tipo)->firstOrFail();

        return compact('plantilla');
    }
}
