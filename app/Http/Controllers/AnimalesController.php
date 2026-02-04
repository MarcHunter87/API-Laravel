<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalesResources;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AnimalesController extends Controller
{
    public function index()
    {
        return AnimalesResources::collection(Animal::all()); // Con collection devolvemos el array solo con lo declarado en el toArray de AnimalesResources
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'nombre' => 'required|string',
                    'tipo' => 'required|string|in:perro,gato,hámster,conejo',
                    'peso' => 'required|numeric',
                    'enfermedad' => 'required|string',
                    'comentarios' => 'nullable|string',
                ],
                [
                    'nombre.required' => 'Es obligatorio poner un nombre.',
                    'tipo.required' => 'Es obligatorio poner un tipo.',
                    'tipo.in' => 'Se debe poner perro, gato, hámster o conejo.',
                    'peso.required' => 'Es obligatorio poner un peso.',
                    'peso.numeric' => 'El peso debe ser un número.',
                    'enfermedad.required' => 'Es obligatorio poner una enfermedad.',
                ]
            );

            $animal = Animal::create($validated);
            return response()->json([
                'mensaje' => "El animal {$animal->nombre} se ha creado correctamente.",
                'data' => new AnimalesResources($animal),
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
        $animal = Animal::find($id);
        if (!$animal) {
            return response()->json(['mensaje' => "El animal con id {$id} no existe."], 404); // Estado 404 Not Found
        }

        try {
            $validated = $request->validate(
                [
                    'nombre' => 'required|string',
                    'tipo' => 'required|string|in:perro,gato,hámster,conejo',
                    'peso' => 'required|numeric',
                    'enfermedad' => 'required|string',
                    'comentarios' => 'nullable|string',
                ],
                [
                    'nombre.required' => 'Es obligatorio poner un nombre.',
                    'tipo.required' => 'Es obligatorio poner un tipo.',
                    'tipo.in' => 'El tipo debe ser uno de los siguientes: perro, gato, hámster, conejo.',
                    'peso.required' => 'Es obligatorio poner un peso.',
                    'peso.numeric' => 'El peso debe ser un número.',
                    'enfermedad.required' => 'Es obligatorio poner una enfermedad.',
                ]
            );
            $animal->update($validated);

            return response()->json([
                'mensaje' => "El animal {$animal->nombre} con id {$id} se ha actualizado correctamente.",
                'data' => new AnimalesResources($animal),
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
        $animal = Animal::find($id);
        if (!$animal) {
            return response()->json(['mensaje' => "El animal con id {$id} no existe."], 404); // Estado 404 Not Found
        }

        $animal->delete();
        return response()->json(['mensaje' => "El animal {$animal->nombre} con id {$id} se ha borrado correctamente."], 200); // Estado 200 OK
    }
}
