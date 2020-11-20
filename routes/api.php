<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Register
Route::post('register', [UserController::class, 'register'])->name('user.register');
//Login
Route::post('login', [UserController::class, 'login'])->name('user.login');


Route::middleware(['auth:api'])->group(function () {
//Forget Password
    Route::post('forgetLink',[UserController::class,'mailForgetLink'])->name('user.forget');
});
//Forget view
Route::get('forget/{user}',[UserController::class,'forget'])->name('forget');
Route::post('forget/{user}',[UserController::class,'forget']);

//Fallback Routes
Route::get('login', function () {
    abort(403);
})->name('login');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Races
Route::get('races', [\App\Http\Controllers\GenericController::class, 'races']);
//Genders
Route::get('genders', [\App\Http\Controllers\GenericController::class, 'genders']);
//States
Route::get('states', [\App\Http\Controllers\GenericController::class, 'states']);
