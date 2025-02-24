<?php

namespace App\Repository;

use App\Models\User;

class UserRepository
{
    // Function get user
    public function getUser(object $data)
    {
        return User::with('type_user')
            ->where([['email', $data->email], ['is_active', 1]])
            ->first();
    }
}
