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
    // I chose to split them up instead of using apiResource.
    // I want to bring a bit more logical structure to this than laravel by default provides.

    // interacting with a single bear
    // ID must be provided in the url when necessary.
    Route::prefix('bear')->group(function () {
        Route::get('/{bear}', [BearController::class, 'show']); // show one bear
        Route::post('/', [BearController::class, 'store']); // make one new bear
        Route::put('/{bear}', [BearController::class, 'update']); // update 1 bear
        Route::delete('/{bear}', [BearController::class, 'delete']); // delete 1 bear
    });

    // interacting with multiple bears
    // ID must be provided in json body when necessary.
    Route::prefix('bears')->group(function () {
        Route::get('/', [BearController::class, 'display']); // show all bears
        Route::post('/', [BearController::class, 'create']); // make multiple new bears
        Route::put('/', [BearController::class, 'modify']); // modify multiple bears
        Route::delete('/', [BearController::class, 'remove']); // remove multiple bears
    });
});

