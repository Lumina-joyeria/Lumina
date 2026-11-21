<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class AsistenteController extends Controller
{
    public function responder(
        Request $request,
        GeminiService $gemini
    ): JsonResponse {
        $datos = $request->validate([
            'mensaje' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $respuesta = $gemini->responder($datos['mensaje']);

            return response()->json([
                'success' => true,
                'respuesta' => $respuesta,
            ]);

        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'El asistente no está disponible en este momento.',
            ], 503);
        }
    }
}