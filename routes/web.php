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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/locale/{locale}', 'LocaleController@store')->name('locale');
Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/menuplan/create', 'HomeController@index');
    Route::get('/menuplan/{menuplan}', 'HomeController@index');
    Route::get('/menuplan/{menuplan}/edit', 'HomeController@index');
    Route::get('/meal/{meal}/edit', 'HomeController@index');
    Route::get('/purchase/{purchase}/edit', 'HomeController@index');
    Route::get('/menuplan/{menuplan}/items', 'HomeController@index');
    Route::get('/menuplan/{menuplan}/shopping-list', 'HomeController@index');
    Route::get('/menuplan/{menuplan}/share', 'HomeController@index');

    Route::get('/menuplan/{menuplan}/shopping-list/pdf', 'ShoppingListPdfController@show');
    Route::get('/menuplan/{menuplan}/pdf', 'MenuplanPdfController@show');
});

Route::middleware('auth')->prefix('/api')->namespace('API')->group(function () {
    Route::get('/menuplan', 'MenuplanController@index');
    Route::post('/menuplan', 'MenuplanController@store');
    Route::get('/menuplan/{menuplan}', 'MenuplanController@show');
    Route::put('/menuplan/{menuplan}', 'MenuplanController@update');
    Route::delete('/menuplan/{menuplan}', 'MenuplanController@destroy');

    Route::get('/menuplan/{menuplan}/meals', 'MealController@index');
    Route::post('/menuplan/{menuplan}/meals', 'MealController@store');
    Route::get('/meal/{meal}', 'MealController@show');
    Route::put('/meal/{meal}', 'MealController@update');
    Route::delete('/meal/{meal}', 'MealController@destroy');

    Route::get('/menuplan/{menuplan}/items', 'ItemController@index');
    Route::post('/menuplan/{menuplan}/items', 'ItemController@store');
    Route::put('/item/{item}', 'ItemController@update');
    Route::delete('/item/{item}', 'ItemController@destroy');

    Route::get('/meal/{meal}/ingredients', 'IngredientController@index');
    Route::post('/meal/{meal}/ingredients', 'IngredientController@store');
    Route::put('/ingredient/{ingredient}', 'IngredientController@update');
    Route::delete('/ingredient/{ingredient}', 'IngredientController@destroy');

    Route::get('/menuplan/{menuplan}/shopping-list', 'ShoppingListController@index');
    Route::get('/purchase/{purchase}/shopping-list', 'ShoppingListController@show');

    Route::get('/menuplan/{menuplan}/invitation', 'MenuplanInvitationController@index');
    Route::post('/menuplan/{menuplan}/invitation', 'MenuplanInvitationController@store');
    Route::post('/invitation/{invitation}/accept', 'AcceptInvitationController@store');
    Route::delete('/invitation/{invitation}/decline', 'AcceptInvitationController@destroy');
    Route::get('/invitation', 'InvitationController@index');
    Route::delete('/invitation/{invitation}', 'InvitationController@destroy');

    Route::get('/menuplan/{menuplan}/purchases', 'PurchaseController@index');
    Route::post('/menuplan/{menuplan}/purchases', 'PurchaseController@store');
    Route::get('/purchase/{purchase}', 'PurchaseController@show');
    Route::put('/purchase/{purchase}', 'PurchaseController@update');
    Route::delete('/purchase/{purchase}', 'PurchaseController@destroy');
});
