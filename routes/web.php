<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->prefix('/api')->namespace('API')->group(function () {
    Route::get('/menuplan', 'MenuplanController@index');
    Route::post('/menuplan', 'MenuplanController@store');
    Route::put('/menuplan/{menuplan}', 'MenuplanController@update');
    Route::delete('/menuplan/{menuplan}', 'MenuplanController@destroy');

    Route::get('/menuplan/{menuplan}/meals', 'MealController@index');
    Route::post('/menuplan/{menuplan}/meals', 'MealController@store');
    Route::put('/meal/{meal}', 'MealController@update');
    Route::delete('/meal/{meal}', 'MealController@destroy');

    Route::get('/menuplan/{menuplan}/items', 'ItemController@index');
    Route::post('/menuplan/{menuplan}/items', 'ItemController@store');
    Route::put('/item/{item}', 'ItemController@update');
    Route::delete('/item/{item}', 'ItemController@destroy');
});
