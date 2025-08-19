<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;

class TypeDecryptNodeMiddleware
{
    /**
     * @throws CustomException
     */
    public function handle(Request $request, Closure $next)
    {
        $headerDecryption = $this->headerDecryption($request->header('decryption'));

        return $next($request->merge([
            'decryption' => $headerDecryption,
        ]));
    }

    private function headerDecryption($header): string
    {
        $decryption = ['accell', 'schp-harold', 'fengbo'];

        if (! in_array($header, $decryption)) {
            throw new CustomException('decryption header no valid', 400);
        }

        return $header;
    }
}
