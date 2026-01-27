<?php

namespace App\Http\Controllers;

use App\Events\SurveyVoted;
use App\Models\Asistencia;
use App\Models\Evento;
use App\Models\Pregunta;
use App\Models\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index()
    {
        // Return active events with questions
        $events = Evento::with(['preguntas'])->orderBy('fecha', 'desc')->get();
        return response()->json($events);
    }

    public function show($id)
    {
        $event = Evento::with(['preguntas.respuestas'])->findOrFail($id);
        
        // Calculate simple stats for each question
        $event->preguntas->each(function($pregunta) {
            $total = $pregunta->respuestas->count();
            $yes = $pregunta->respuestas->where('respuesta', true)->count();
            $pregunta->stats = [
                'total' => $total,
                'yes' => $yes,
                'no' => $total - $yes,
                'yes_percent' => $total > 0 ? round(($yes / $total) * 100) : 0,
            ];
            unset($pregunta->respuestas); // Hide individual answers
        });

        return response()->json($event);
    }

    public function vote(Request $request, $id)
    {
        $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'respuesta' => 'required|boolean',
        ]);

        $user = $request->user();
        if (!$user || !$user->persona) {
            return response()->json(['message' => 'User not associated with a person'], 400);
        }

        $asistencia = Asistencia::firstOrCreate([
            'id_persona' => $user->persona->id,
            'id_evento' => $id,
        ], [
            'hora' => now()->format('H:i:s'),
        ]);

        // Check if already voted for this question
        $existingVote = Respuesta::where('id_asistente', $asistencia->id)
            ->where('id_pregunta', $request->pregunta_id)
            ->first();

        if ($existingVote) {
            $existingVote->update(['respuesta' => $request->respuesta]);
        } else {
            Respuesta::create([
                'id_asistente' => $asistencia->id,
                'id_pregunta' => $request->pregunta_id,
                'respuesta' => $request->respuesta,
            ]);
        }

        // Calculate new stats for the specific question to broadcast
        $pregunta = Pregunta::with('respuestas')->find($request->pregunta_id);
        $total = $pregunta->respuestas->count();
        $yes = $pregunta->respuestas->where('respuesta', true)->count();
        $results = [
            'pregunta_id' => $pregunta->id,
            'stats' => [
                'total' => $total,
                'yes' => $yes,
                'no' => $total - $yes,
                'yes_percent' => $total > 0 ? round(($yes / $total) * 100) : 0,
            ]
        ];

        // Broadcast event
        SurveyVoted::dispatch($id, $results);

        return response()->json(['message' => 'Vote recorded', 'results' => $results]);
    }
}
