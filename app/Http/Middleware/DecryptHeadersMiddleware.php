<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Blocktrail\CryptoJSAES\CryptoJSAES;
use Closure;
use Illuminate\Http\Request;
use stdClass;

class DecryptHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {

            $headers = $request->header('info');
            if ($headers === null) {
                throw new CustomException('info headers is required', 400);
            }

            if (env('APP_ENV') == 'dev') {
                $data = new stdClass;
                if (is_string($headers)) {
                    $data = json_decode($headers);
                } else {
                    foreach ($headers as $clave => $valor) {
                        $data->$clave = $valor;
                    }
                }

                return $next($request->merge(['api' => $data]));
            }

            $headers = preg_replace('/[~]/', '/', $headers);
            $data = base64_decode($headers);

            $data = json_decode(CryptoJSAES::decrypt($data, env('APP_KEY')));
            if (is_string($data)) {
                $data = json_decode($data);
            }

            return $next($request->merge(['api' => $data]));
        } catch (CustomException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
