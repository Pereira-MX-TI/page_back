<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Helpers\BuildData;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use App\Models\Token;

use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class TokenController extends Controller
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        try {
            ValidatorController::validatorData($request->info, [
                'email' => 'required|email',
                'password' => 'required',
                'device' => 'required'
            ]);

            $data = $request->info;
            $user = $this->userRepository->getUser($data);

            if (!$user) {
                throw new CustomException('Email or password invalid', 420);
            }
            $token = auth()->attempt(['email' => $data->email, 'password' => $data->password]);

            if (!$token) {
                throw new CustomException('Email or password invalid', 420);
            }

            // Manager tokens
            $this->managerTokens($user,$token,$data);

            return response([
                'message' => 'Successfully logged in',
                'info' => BuildData::BuildDataLogin($token, $user),
            ], ResponseHttp::HTTP_OK);
        } catch (CustomException $e) {
            return response([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function logout()
    {
        auth()->logout();
        return response([
            'message' => 'User successfully logged out.'
        ], ResponseHttp::HTTP_OK);
    }


    private function managerTokens(Object $user,$token,$data)
    {
        if ($user['Type_user']['id'] != 4) {
            $tokenEnable = Token::where([['user_id', $user['id']], ['is_active', 1]])
                ->orderBy('id', 'DESC')
                ->get();

            if (count($tokenEnable) >= 2) {
                $tokenEnable = $tokenEnable[0];
                Token::where([['user_id', $user['id']], ['is_active', 1], ['id', '!=', $tokenEnable['id']]])
                    ->update(['is_active' => 0]);
            }
        }

        Token::create([
            'data' => $token,
            'user_id' => $user['id'],
            'device' => $data->device,
            'is_active' => 1
        ]);
    }
}
