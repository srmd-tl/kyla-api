<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    \App\Utils\Helper::sendMessage("ello","+923315743763");
    return view('welcome');
});
Route::get('/kylaProcess/{kylaProcess}', [\App\Http\Controllers\KylaProcessController::class, 'show'])
    ->name('kylaProcess.show');
Route::get('stream/{folderName}/{filename}',function($folder,$filename){
    $path = sprintf("/storage/%s/%s",$folder, $filename);
    return response()->file(public_path($path),[
        'Content-Type' => 'video/3gpp',
        'Content-Disposition' => 'inline;'
    ]);
});
