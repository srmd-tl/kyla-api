<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register()
    {
        request()->validate(
            [
                "name" => "required|max:50",
                "email" => "required|unique:users|email",
                "race" => "required",
                "gender" => "required",
                "age" => "required",
                "state" => "required"
            ]
        );
        $data =
            [
                "name" => request()->name,
                "email" => request()->email,
                "race" => request()->race,
                "gender" => requiest()->gender,
                "age" => request()->age,
                "state" => request()->state
            ];
        $user = User::create($data);
        return response()->success($user);
    }
}
