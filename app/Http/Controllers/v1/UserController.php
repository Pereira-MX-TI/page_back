<?php

namespace App\Http\Controllers\v1;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Models\v1\Image;
use App\Http\Models\v1\Privacy;
use App\Http\Models\v1\Request_change;
use App\Http\Models\v1\Token;
use App\Http\Models\v1\User;
use App\Http\Models\v1\User_api;
use App\Http\Models\v1\User_location;
use App\Http\Models\v1\User_type;
use App\Http\Resources\v1\UserAdminApiResource;
use App\Http\Resources\v1\UserApiResource;
use App\Mail\ChangePasswordMailable;
use App\Repositories\AutoCompleteRepository;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @group User
 *
 * APIs for managing User
 */
class UserController extends Controller
{
    private $autoCompleteRepository;

    public function __construct(
        AutoCompleteRepository $autoCompleteRepository
    ) {
        $this->autoCompleteRepository = $autoCompleteRepository;

    }

}
