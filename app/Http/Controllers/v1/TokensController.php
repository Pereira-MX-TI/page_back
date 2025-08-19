<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Models\v1\Token;
use App\Http\Models\v1\User;
use App\Http\Models\v1\Users_site;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

/**
 * @group Tokens
 *
 * APIs for managing Tokens
 */
class TokensController extends Controller
{
    /**
     * @description: Get browser
     */
    private function getBrowser($request): string
    {
        return $request->headers->all('user-agent')[0];
    }

    /**
     * @description: Method for create token(login)
     *
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function login(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'email' => 'required',
                'password' => 'required',
            ]);

            $payload = $request->info;

            $user = User::with('type_user')
                ->where('email', Str::lower($payload->email))
                ->where('is_active', 1)
                ->orderBy('id', 'DESC')
                ->get()->first();

            if (! $user) {
                throw new CustomException('Email or password invalid', 420);
            }

            $token = auth()
                ->attempt(
                    [
                        'email' => Str::lower($payload->email),
                        'password' => $payload->password,
                    ]
                );

            if (! $token) {
                throw new CustomException('Email or password invalid', 420);
            }

            $this->tokenEnable($user);

            Token::create([
                'id' => date('ymd') . date('Hi') . date('s') . substr(microtime(), 2, 3),
                'data' => $token,
                'user_id' => $user['id'],
                'device' => $this->getBrowser($request),
                'is_active' => 1,
            ]);

            return response(['data' => $this->getDataLogin($user, $token)], ResponseHttp::HTTP_OK);
        } catch (CustomException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    /**
     * @description: Validate token and number of user login
     */
    private function tokenEnable($user): void
    {
        $tokenEnable = Token::where('user_id', $user->id)
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')->get();

        if (count($tokenEnable) >= 2) {
            $tokenEnable = $tokenEnable[0];
            Token::where('user_id', $user->id)
                ->where('is_active', 1)
                ->where('id', '!=', $tokenEnable['id'])
                ->update(['is_active' => 0]);
        }
    }

    private static function getDataLogin($user, $token)
    {
        $data = [
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'privacy_policy' => $user['privacy_policy'],
                'type_user' => $user['type_user'],
            ],
            'quantity_sites' => Users_site::where([['user_id', $user['id']], ['is_active', 1]])->get()->count(),
        ];

        return $data;
    }
}
