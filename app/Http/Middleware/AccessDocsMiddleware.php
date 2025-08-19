<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class AccessDocsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $code = $request->query('code'); // 'id' es el nombre del parámetro de la ruta

        if ($code !== config('app.documentation_access_code')) {

            return response()->json(['message' => 'No autorizado'], ResponseHttp::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
