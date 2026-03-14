<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 *
 * Verifica que el usuario autenticado posea un rol permitido.
 * Acepta una lista de IDs de rol y valida que el `rol_id` del usuario exista en dicha lista.
 *
 * Respuestas de error:
 * - 401 JSON cuando el usuario no está autenticado.
 * - 403 JSON cuando el usuario no tiene permisos.
 */
class CheckRole
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param Request $request  La petición HTTP actual.
     * @param Closure $next     El siguiente middleware/controlador.
     * @param string  ...$roles IDs de roles permitidos (p. ej. '1', '2').
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        $allowedRoleIds = array_map('intval', $roles);
        $userRoleId = (int) ($user->rol_id ?? 0);

        if (!in_array($userRoleId, $allowedRoleIds, true)) {
            return response()->json(['message' => 'No tienes permisos para acceder a este recurso.'], 403);
        }

        return $next($request);
    }
}

