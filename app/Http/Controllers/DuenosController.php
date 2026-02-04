<?php

namespace App\Http\Controllers;

use App\Http\Resources\DuenosResources;
use App\Models\Dueno;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DuenosController extends Controller
{
    public function index()
    {
        return DuenosResources::collection(Dueno::all()); // Con collection devolvemos el array como Resource
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'nombre' => 'required|string',
                    'apellido' => 'required|string',
                    'id_animal' => 'required|exists:animales,id_animal', // Comprueba que la id_animal existe en animales
                ],
                [
                    'nombre.required' => 'Es obligatorio poner un nombre.',
                    'apellido.required' => 'Es obligatorio poner un apellido.',
                    'id_animal.required' => 'Es obligatorio poner un id de animal.',
                    'id_animal.exists' => 'No existe un animal con ese id.',
                ]
            );

            $dueno = Dueno::create($validated);
            return response()->json([
                'mensaje' => "El dueño {$dueno->nombre} {$dueno->apellido} se ha creado correctamente.",
                'data' => new DuenosResources($dueno),
            ], 201); // Estado 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación.',
                'errores' => $e->errors(),
            ], 422); // Estado 422 Unprocessable Entity
        }
    }

    public function update(Request $request, $id)
    {
        $dueno = Dueno::find($id);
        if (!$dueno) {
            return response()->json(['mensaje' => "El dueño con id {$id} no existe."], 404); // Estado 404 Not Found
        }

        try {
            $validated = $request->validate(
                [
                    'nombre' => 'required|string',
                    'apellido' => 'required|string',
                    'id_animal' => 'required|exists:animales,id_animal', // Comprueba que la id_animal existe en animales
                ],
                [
                    'nombre.required' => 'Es obligatorio poner un nombre.',
                    'apellido.required' => 'Es obligatorio poner un apellido.',
                    'id_animal.required' => 'Es obligatorio poner un id de animal.',
                    'id_animal.exists' => 'No existe un animal con ese id.',
                ]
            );

            $dueno->update($validated);
            return response()->json([
                'mensaje' => "El dueño {$dueno->nombre} {$dueno->apellido} con id {$id} se ha actualizado correctamente.",
                'data' => new DuenosResources($dueno),
            ], 200); // Estado 200 OK
        } catch (ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación.',
                'errores' => $e->errors(),
            ], 422); // Estado 422 Unprocessable Entity
        }
    }

    public function destroy($id)
    {
        $dueno = Dueno::find($id);
        if (!$dueno) {
            return response()->json(['mensaje' => "El dueño con id {$id} no existe."], 404); // Estado 404 Not Found
        }

        $id_animal = $dueno->id_animal;
        $nombre = $dueno->nombre;
        $apellido = $dueno->apellido;

        $dueno->delete();

        $animal = Animal::find($id_animal);
        if ($animal) {
            $animal->delete();
        }

        return response()->json(['mensaje' => "El dueño {$nombre} {$apellido} con id {$id} y su animal con id {$id_animal} se han borrado correctamente."], 200); // Estado 200 OK
    }
}
