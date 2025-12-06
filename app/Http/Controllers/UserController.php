<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserData()
    {
        $User = User::selectedFields()->get();

        return UserResource::collection($User);
    }
}
