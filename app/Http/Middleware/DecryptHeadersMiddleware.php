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

            $headerInfo = $this->headerInfo($request->header('info'));

            if ($headerInfo === null) {
                throw new CustomException('info headers is required', 400);
            }
            return $next($request->merge([
                'api' => $headerInfo,
            ]));
        } catch (CustomException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    private function headerInfo($header)
    {
        if ($header === null) {
            throw new CustomException('info headers is required', 400);
        }

        if (config('app.env') == 'dev') {
            $data = new stdClass();
            if (is_string($header)) {
                $data = json_decode($header);
            } else {
                foreach ($header as $clave => $valor) {
                    $data->$clave = $valor;
                }
            }

            return $data;
        }

        $headers = preg_replace('/[~]/', '/', $header);
        $data = base64_decode($headers);
        $data = json_decode(CryptoJSAES::decrypt($data, config('app.key')));

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return $data;
    }
}
