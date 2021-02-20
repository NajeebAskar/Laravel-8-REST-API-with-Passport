<?php
namespace App\Http\Controllers\Api;
//use App\Http\Controllers\Api\RegisterController;
//use App\Http\Controllers\Api\UserController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});






Route::group(['prefix'=>'v1'], function () {
    Route::post('login', [RegisterController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [RegisterController::class, 'logout']);

        Route::get('user/{userId}/detail', [UserController::class, 'show']);
//        Route::post('product', [ProductController::class, 'store']);
        Route::apiResource('product', 'ProductController');
    });
});

Route::fallback(function() {
    return response()->json([
        'Hm, why did you land here somehow?'
    ], 404);
});
