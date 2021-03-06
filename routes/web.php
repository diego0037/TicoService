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
    return view('auth.login');
});

Auth::routes();
// Route::post('newUser', 'Auth\RegisterController@register');
Route::get('/home', 'HomeController@index');
Route::post('loginUser', 'Auth\LoginController@Login');
Route::get('/user/activation/{token}', 'Auth\RegisterController@userActivation');

Route::group(['prefix' => 'services'], function(){
  Route::get('/', 'ServiceController@index');
  Route::get('create', 'ServiceController@create');
  Route::post('store', 'ServiceController@store');
  Route::get('{id}/destroy', 'ServiceController@destroy');
  Route::put('{service}/{id}/update', 'ServiceController@update');
});
//
// Route::post('services', 'ServiceController');
