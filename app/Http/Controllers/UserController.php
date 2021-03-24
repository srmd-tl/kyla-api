<?php

namespace App\Http\Controllers;

use App\Mail\ForgetPassword;
use App\Mail\VerficationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mail;

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

        if (request()->photo) {
            $path = request()->file('photo')->store("avatars", "public");
            $data["photo"] = $path;
        }
        $user = User::create($data);
        //send verification email
        Mail::to($user)->send(new VerficationMail($user));
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
            if(!$user->email_verified_at)
            {
                return response()->error("Email not verified");

            }
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
        request()->validate(["email" => "required"]);
        $user = User::whereEmail(request()->email)->first();
        if (!$user) {
            return response()->error("Record does not exist");
        }

        try {
            Mail::to(request()->email)->send(new ForgetPassword($user->id));
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
            return view('newPassword', ["id" => $user->id]);
        }
        $user->update(["password" => Hash::make(request()->password), "api_token" => null]);
        return "Password UPdated!";
    }

    public function verify(User $user)
    {
        if (request()->method() == "GET") {
            if (!request()->hasValidSignature()) {
                abort(401);
            }
            $user->update(["email_verified_at" => Carbon::now()]);
            return "User Verified";
        }
        Mail::to($user)->send(new VerficationMail($user));
        return response()->success("Mail resent");

    }

    public function updateProfile()
    {
        $user = auth()->user();
        $data = [
            "name" => request()->name ?? $user->name,
            "email" => request()->email ?? $user->email,
            "age" => request()->age ?? $user->age,
            "gender" => request()->gender ?? $user->gender,
            "state" => request()->state ?? $user->state,
            "race" => request()->race ?? $user->race,
        ];
        if (request()->password) {
            $data["password"] = Hash::make(request()->password);
        }
        if (request()->photo) {
            $path = request()->file('photo')->store('avatars', "public");
            $data["photo"] = $path;
        }
        $user->update($data);
        return response()->success("Profile Updated");
    }

    public function profile()
    {
        return response()->success(request()->user());
    }

    public function logout()
    {
        auth()->user()->update(["api_token" => null]);
        auth()->user()->logout();
        return response()->success("Logged Out!!");
    }
}

