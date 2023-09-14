<?php

use App\Http\Controllers\BearController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

// I didn't make a controller for this, because this is the only thing I need to do for this part. If I needed to do more than I would've.
Route::get('/gen-token/{username}', function($username) {
    $user = User::where('name', $username)->first();
    $user->tokens()->delete();
    $token = $user->createToken('api-token');
    $token_text = $token->plainTextToken;
    $stripped_token = Str::replaceFirst('|', '', $token_text);
    return response()
        ->json([
            'username' => "$user->name",
            'api-token' => $token_text,
        ]);
});

Route::middleware(['auth:sanctum'])->group(function() {
    Route::apiResource('bears', BearController::class);
});

