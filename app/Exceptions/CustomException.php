<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class CustomException extends Exception
{
    public static function unauthorized(): self
    {
        return new self('Unauthorized', ResponseHttp::HTTP_UNAUTHORIZED);
    }

    public static function notFound($message): self
    {
        return new self($message, ResponseHttp::HTTP_NOT_FOUND);
    }
}
