<?php

use App\Http\Controllers\MentorController;
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



Route::prefix("/v1/mentors")->group(function (){
    Route::put("/{mentors}", [MentorController::class, "update"]);
    Route::get("/{id}", [MentorController::class, "show"]);
    Route::delete("/{id}", [MentorController::class, "destroy"]);
    Route::post("/", [MentorController::class, "store"]);
    Route::get("/", [MentorController::class, "index"]);
});

Route::prefix("/v1/chapters")->group(function (){
    Route::put("/{chapter}", [MentorController::class, "update"]);
    Route::get("/{id}", [MentorController::class, "show"]);
    Route::delete("/{id}", [MentorController::class, "destroy"]);
    Route::post("/", [MentorController::class, "store"]);
    Route::get("/", [MentorController::class, "index"]);
});

