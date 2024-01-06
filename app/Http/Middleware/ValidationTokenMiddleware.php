<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\CustomException;
use App\Http\Models\v1\Token;
use Illuminate\Http\Request;


class ValidationTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = str_replace('Bearer ', '', $request->header('Authorization'));
            $validationToken = Token::where('data', $token)
                ->where('is_active', 1)->get()->count();

            if ($validationToken == 0) {
                throw CustomException::unauthorized();
            }

            return $next($request);
        } catch (CustomException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }
}
