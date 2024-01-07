<?php

namespace App\Http\Helpers;

class BuildData
{
    public static function BuildDataLogin(string $token,Object $user): array
    {
        return [
            'token' => $token,
            'user' => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "type_user" => $user['type_user']
            ]
        ];
    }

}
