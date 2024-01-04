<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use App\Models\Response;

use Blocktrail\CryptoJSAES\CryptoJSAES;

class TokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {    
        $validation = ValidatorController::validatorGral($request,
        [   
            'email' => 'required|email',
            'password' => 'required',
            'device' => 'required'
        ],1);

        if($validation['code']!=200)
            return response()->json($validation,$validation['code']);

        $data = $validation['data'];
        $user = User::with('type_user')->where([['email', $data['email']],['is_active',1]])->orderBy('id', 'DESC')->get();
            
        if(count($user)==0)
        {
            $code = Response::where('code', '404')->get()->first();
            return response()->json($code, $code['code']);
        }

        $user = $user[0];
        $token = auth()->attempt(['email'=>$data['email'],'password'=>$data['password']]);

        if ($token) 
        {
            if($user['Type_user']['id']!=4)
            {
                $tokenEnable = Token::where([['user_id',$user['id']],['is_active',1]])->orderBy('id','DESC')->get();

                if(count($tokenEnable)>=2)
                {
                    $tokenEnable = $tokenEnable[0];
                    Token::where([['user_id',$user['id']],['is_active',1],['id','!=',$tokenEnable['id']]])->update(['is_active'=>0]);
                }
            }

            $token = CryptoJSAES::encrypt(json_encode($token),strval(env('APP_KEY')));
            Token::create([
                'data'     => $token,
                'user_id'  => $user['id'],
                'device'   => $data['device'],
                'is_active'=> 1
            ]);

            $data = [
                'token' => $token,
                'user' => [
                    "id"=> $user['id'],
                    "name"=> $user['name'],
                    "email"=> $user['email'],
                    "type_user" => $user['type_user']
                ]
            ];

            return response()->json(["data" => CryptoJSAES::encrypt(json_encode($data), strval(env('APP_KEY')))], 200);
        }
        else 
        {
            $code = Response::where('code', '401')->get()->first();
            return response()->json($code, $code['code']);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully logged out.']);
    }
}
