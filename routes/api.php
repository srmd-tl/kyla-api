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
//Forget Password Link Generator
    Route::post('forgetLink', [UserController::class, 'mailForgetLink'])->name('user.forget');
    //Update User Profile
    Route::post('updateProfile', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    //Get Profile
    Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
    //Get User Contacts
    Route::get('user/contact', [\App\Http\Controllers\ContactController::class, 'get'])
        ->name('user.contacts');
    //Add Contact
    Route::post('user/contact', [\App\Http\Controllers\ContactController::class, 'store'])
        ->name('contact.store');
    //Update Contact
    Route::put('user/contact/{contact}', [\App\Http\Controllers\ContactController::class, 'update'])
        ->name('contact.update');
    //Logout
    Route::post('logout', [UserController::class, 'logout'])->name('user.logout');
    //Store audio file
    Route::post('kyla/process/audio', [\App\Http\Controllers\UserFileController::class, 'audioStore'])
        ->name('audio.store');
    //Get audio files
    Route::get('kyla/process/audio', [\App\Http\Controllers\UserFileController::class, 'audioFiles'])
        ->name('audio.get');
    //Store Video file
    Route::post('kyla/process/video', [\App\Http\Controllers\UserFileController::class, 'videoStore'])
        ->name('video.store');
    //Get Video files
    Route::get('kyla/process/video', [\App\Http\Controllers\UserFileController::class, 'videoFiles'])
        ->name('video.get');
    //Store Officer
    Route::post('kyla/process/officer', [\App\Http\Controllers\OfficerController::class, 'officer'])
        ->name('officer.store');
    //Get Officer
    Route::get('kyla/process/officer', [\App\Http\Controllers\OfficerController::class, 'getOfficer'])
        ->name('officer.get');


});
//Forget view
Route::get('forget/{user}', [UserController::class, 'forget'])->name('forget');
//Password Update
Route::put('forget/{user}', [UserController::class, 'forget'])->name('password.update');

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
//Laws
Route::get('laws', [\App\Http\Controllers\GenericController::class, 'laws'])->name('laws.get');
