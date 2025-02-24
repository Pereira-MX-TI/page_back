<?php

namespace App\Http\Middleware;

use Blocktrail\CryptoJSAES\CryptoJSAES;
use Closure;
use Illuminate\Http\Request;

class EncryptDataMiddleware
{
    /**
     * @Description: Encrypt data response
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400 && $statusCode < 600) {
            return $response;
        }

        $content = $response->getContent();
        if (env('APP_ENV') == 'dev') {
            $response->setContent(['data' => json_decode($content, true)]);

            return $response;
        }

        $encryptedContent = CryptoJSAES::encrypt($content, strval(env('APP_KEY')));
        $response->setContent(['data' => $encryptedContent]);

        return $response;
    }
}
