<?php

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

Route::group(['prefix' => '/auth'],function(){
    Route::post('/register',[\App\Http\Controllers\Auth\AuthController::class, 'register']);
    Route::middleware('user_auth')->post('/login',[\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::middleware('auth:api')->post('/check',[\App\Http\Controllers\Auth\AuthController::class, 'check']);
    Route::middleware('auth:api')->post('/logout',[\App\Http\Controllers\Auth\AuthController::class, 'logout']);

});

// [ADMIN ONLY] ADMIN 
Route::group(['prefix' => '/admin/resources','middleware' => 'auth:api'],function(){
    Route::middleware('admin_only')->apiResource('users', \App\Http\Controllers\Admin\UsersController::class);
    Route::middleware('admin_only')->apiResource('art_channels', \App\Http\Controllers\Admin\ArtChannelsController::class);
});


// COMMUNITY
Route::group(['prefix' => '/community/resources'],function(){

    // [PUBLIC] interface
    Route::group(['prefix' => '/interface'], function() {
        Route::get('/upload-select-list', [\App\Http\Controllers\Community\InterfaceController::class, 'uploadSelectList']);
        Route::get('/get-arts-list/{dimension_id}-{channel_id}', [\App\Http\Controllers\Community\InterfaceController::class,'artsList']);
        Route::get('/get-slide-arts/{type}',[\App\Http\Controllers\Community\InterfaceController::class,'getSlideArts']);
        Route::get('/get-filters-list',[\App\Http\Controllers\Community\InterfaceController::class,'getFiltersList']);
    });


    // [AUTHORIZATION is REQUIRED]
    Route::group(['middleware' => 'auth:api'],function(){
        Route::apiResource('arts',\App\Http\Controllers\Community\ArtsController::class);
        // EDIT ART by USER
        Route::middleware('arts_authorize_check')->post('/arts/edit/{art}',[\App\Http\Controllers\Community\ArtsController::class,'update']);
        // DELETE ART by USER
        Route::middleware('arts_authorize_check')->get('/arts/delete/{art}',[\App\Http\Controllers\Community\ArtsController::class,'destroy']);

        // Route::apiResource('showcaseArts',\App\Http\Controllers\Community\ShowcaseArtsController::class);
        Route::apiResource('showcases',\App\Http\Controllers\Community\ShowcasesController::class);
        // EDIT ART by USER
        Route::middleware('showcases_authorize_check')->post('/showcases/edit/{showcase}',[\App\Http\Controllers\Community\ShowcasesController::class,'update']);
        Route::middleware('showcases_authorize_check')->get('/showcases/delete/{showcase}',[\App\Http\Controllers\Community\ShowcasesController::class,'destroy']);

        Route::middleware('authorize_check')->apiResource('users',\App\Http\Controllers\Community\UsersController::class);
    });

    // [AUTHORIZATION is NOT REQUIRED]
    Route::get('/showcases/get/{id}',[\App\Http\Controllers\Community\ShowcasesController::class,'show']);
    Route::get('/showcases/get-list/{userId}',[\App\Http\Controllers\Community\ShowcasesController::class,'index']);

    Route::get('/arts/get-list/{userId}',[\App\Http\Controllers\Community\ArtsController::class,'index']);
    Route::get('/arts/get/{id}',[\App\Http\Controllers\Community\ArtsController::class,'show']);

    Route::get('/users/get/{id}',[\App\Http\Controllers\Community\UsersController::class,'show']);

    // [AUTH ONLY]
    Route::group(['prefix' => '/auth-only'],function() {
        // unused
    });
});
