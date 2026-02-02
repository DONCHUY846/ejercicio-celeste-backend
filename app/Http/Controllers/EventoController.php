<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Pregunta;
use App\Models\Usuario;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Exception;

class EventoController extends Controller
{
    /**
     * Store a newly created event with questions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'required|string',
            'preguntas' => 'required|array|min:1',
            'preguntas.*' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Create Evento
            $evento = Evento::create([
                'fecha' => $validated['fecha'],
                'descripcion' => $validated['descripcion'],
            ]);

            // Create Questions
            foreach ($validated['preguntas'] as $preguntaText) {
                Pregunta::create([
                    'id_evento' => $evento->id,
                    'pregunta' => $preguntaText,
                ]);
            }

            // Send notification to all users
            $users = Usuario::all();
            Notification::send($users, new GenericNotification(
                'Nuevo Evento Disponible',
                "Se ha creado un nuevo evento: {$evento->descripcion}",
                "/events/{$evento->id}"
            ));

            DB::commit();

            // Return response with 201 status
            return response()->json($evento->load('preguntas'), 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el evento: ' . $e->getMessage()], 500);
        }
    }
}
