<?php

namespace App\Http\Controllers;

use App\Mail\ForgetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * @return mixed
     */
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

    public function login()
    {
        $flag = false;
        $token = Str::random(60);
        request()->validate(["email" => "required", "password" => "required"]);
        $user = User::whereEmail(request()->email)->first();
        if ($user && Hash::check(request()->password, $user->password)) {
            $user->update(['api_token' => hash('sha256', $token)]);
            Auth::login($user);
            $flag = true;
        }
        if ($flag) {
            return response()->success($token);
        }
        return response()->error("Invalid Email or Password");

    }

    public function mailForgetLink()
    {
        try {
            Mail::to(auth()->user()->email)->send(new ForgetPassword());
        } catch (\Exception $exception) {
            return response()->error($exception->getMessage());
        }
        return response()->success("Mail Link Sent In Email");
    }

    public function forget(User $user)
    {
        if (request()->method() == "GET") {
            if (!request()->hasValidSignature()) {
                abort(401);
            }
            return view('newPassword',["id"=>$user->id]);
        }
        $user->update(["password" => Hash::make(request()->password)]);
        return "Password UPdated!";
    }

}
