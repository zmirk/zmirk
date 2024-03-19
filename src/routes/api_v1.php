<?php

use App\Http\Controllers\Api\PersonalAccessTokenController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/token', [PersonalAccessTokenController::class, 'store'])->name('api_v1.tokens.create');
Route::middleware('auth:sanctum')->delete('/token', [PersonalAccessTokenController::class, 'destroy'])->name('api_v1.tokens.delete');

Route::group([
    'middleware' => ['auth:sanctum', 'throttle:60,1', 'abilities:user:can'],
], function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->tokenCan('user:can') ? $request->user() : 'Не разрешено';
    });

    Route::post('/test', function (Request $request): JsonResponse {
        return response()->json([
            'message' => 'Test!',
            'user' => $request->toArray(),
        ], 200);
    });
});
