<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                "state" => "required",
                "password" => "required"
            ]
        );
        $data =
            [
                "name" => request()->name,
                "email" => request()->email,
                "race" => request()->race,
                "gender" => request()->gender,
                "age" => request()->age,
                "state" => request()->state,
                "password" => Hash::make(request()->password)
            ];
        $user = User::create($data);
        return response()->success($user);
    }
}
