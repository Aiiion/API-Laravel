<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeListController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
// Log::info(request()->url());
// Log::info(request()->urlEncoded());

Route::controller(AuthController::class)->group(function () {
    
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::controller(RecipeListController::class)->group(function () {
    
    Route::post('/recipe-lists/create', 'create');
    Route::get('/recipe-lists/index', 'index');
    Route::post('/recipe-lists/delete', 'delete');
    Route::post('/recipe-lists/update', 'update');
    Route::post('/recipe-lists/remove', 'remove');
});
Route::get('recipes/complexSearch', function() {

    if(!Auth::user()) return response('not logged in', 403);

    $url = "https://api.spoonacular.com/recipes/complexSearch";

    $response = Http::withHeaders([
        'x-api-key' => env('API_KEY')
    ])->get($url, request());
    
    Log::info($response);
    return $response;
});

Route::get('recipes/{id}', function($id) {

    if(!Auth::user()) return response('not logged in', 403);
    
    $url = "https://api.spoonacular.com/recipes/".$id."/ingredientWidget.json";

    $response = Http::withHeaders([
        'x-api-key' => env('API_KEY')
    ])->get($url);
    
    Log::info($response);
    return $response;
});
