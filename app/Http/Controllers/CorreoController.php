<?php

namespace App\Http\Controllers;

use App\Models\Correo;

// Falta todo y verificar mostrar
abstract class CorreoController extends Controller
{
    public function index()
    {
        echo 'entro a index de correo controller';
    }

    public function show($id)
    {
        $correo = Correo::findOrFail($id);

        return response()->json($correo);
    }
}
