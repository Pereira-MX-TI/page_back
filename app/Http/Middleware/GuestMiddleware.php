<?php

namespace App\Http\Middleware;

use App\Helpers\GuestTokens\GenerateGuestToken;
use App\Repositories\GuestTokesRepository;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;

class GuestMiddleware
{
    private $GuestTokesRepository;

    public function __construct(GuestTokesRepository $GuestTokesRepository)
    {
        $this->GuestTokesRepository = $GuestTokesRepository;
    }

    public function handle($request, Closure $next)
    {
        try {
            $token = str_replace('Bearer ', '', $request->header('Authorization'));

            $data = $this->GuestTokesRepository->getTokenByToke($token);

            if (! $data) {
                return response()->json(['error' => 'Token invalid'], 401);
            }

            $token_data = GenerateGuestToken::validateToken($token);

            if (! $token_data) {
                return response()->json(['error' => 'Token invalid'], 401);
            }

            $request->merge(['tokenGuest' => $data]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        return $next($request);
    }
}
