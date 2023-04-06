<?php

use App\Http\Controllers\MakeController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
        // return new UserResource(User::with(['makes'])->where('id', $request->user()->id)->first());
    });

    Route::prefix('makes')->group(function () {
        Route::get('/', [MakeController::class, 'index']);
        Route::get('/{make}', [MakeController::class, 'show']);
        Route::put('/{make}', [MakeController::class, 'update']);
        Route::post('/', [MakeController::class, 'store']);
        Route::delete('/{make}', [MakeController::class, 'destroy']);
    });
});
