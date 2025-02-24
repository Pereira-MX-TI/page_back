<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Blocktrail\CryptoJSAES\CryptoJSAES;
use Closure;
use Illuminate\Http\Request;
use stdClass;

class DecryptDataMiddleware
{
    /**
     * @Description Decrypt data from request
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {

            $info = $request->input('data');
            if ($info === null) {
                throw new CustomException('data is required', 400);
            }

            if (env('APP_ENV') == 'dev') {
                $data = new stdClass;
                if (is_string($info)) {
                    $data = json_decode($info);
                } else {
                    foreach ($info as $clave => $valor) {
                        $data->$clave = $valor;
                    }
                }

                return $next($request->merge(['info' => $data]));
            }

            if ($request->method() == 'GET') {
                $info = preg_replace('/[~]/', '/', $info);
                $data = base64_decode($info);
            } else {
                $data = $info;
            }
            $data = json_decode(CryptoJSAES::decrypt($data, env('APP_KEY')));
            if (is_string($data)) {
                $data = json_decode($data);
            }

            return $next($request->merge(['info' => $data]));

        } catch (CustomException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
