<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Models\v1\Customer;
use App\Http\Models\v1\Location;
use App\Http\Models\v1\Response;
use App\Http\Models\v1\Section;
use App\Http\Models\v1\Token;
use App\Http\Models\v1\Url_api;
use App\Http\Models\v1\User_api;
use App\Http\Models\v1\User_location;
use Blocktrail\CryptoJSAES\CryptoJSAES;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;
use Tymon\JWTAuth\Facades\JWTAuth;

class ValidatorController extends Controller
{
    /**
     * @Description Validar los datos de entrada, en request
     *
     * @throws CustomException
     */
    public static function validatorData($data, $params, $return = false)
    {
        $validator = Validator::make(get_object_vars($data), $params);

        if ($return) {
            if ($validator->fails()) {
                return false;
            }
        } else {

            if ($validator->fails()) {
                $errores = $validator->errors()->all();
                throw CustomException::notFound(implode(',', $errores));
            }
        }

        return true;
    }

    /**
     * @Description Guard of endpoints
     *
     * @throws CustomException
     */
    public static function validatorPermissionV2($type_user)
    {
        $user = auth()->user();
        if (! in_array($user['type_user_id'], $type_user)) {
            throw new CustomException('You do not have permissions to perform this action', ResponseHttp::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @throws CustomException
     */
    public static function validationPassword($email, $password)
    {
        if (! JWTAuth::attempt(['email' => $email, 'password' => $password])) {
            throw new CustomException('Password incorrect', 412);
        }
    }
}
