<?php

use App\Http\Controllers\NotesController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
Route::post('uploadNote',[NotesController::class,'createNotes']);
Route::post('/addCollabarator',[NotesController::class,'addCollabarator']);
Route::get('/getNotes',[NotesController::class,'getNotes']);
Route::post('/removeMail',[NotesController::class,'removeMailFromCollabarator']);