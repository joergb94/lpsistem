<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('/login');
});

Auth::routes();
Route::group(['middleware'=>['auth']], function(){
    
Route::get('logout', 'Auth\LoginController@redirectUrlLogout');
Route::get('/home', 'HomeController@index')->name('home');
//user
Route::get('/users', 'UserController@index');
Route::post('/users/add', 'UserController@store');
Route::post('/users/update', 'UserController@update');
Route::post('/users/change_status','UserController@change_status');
Route::post('/users/deleteOrResotore','UserController@deleteOrResotore');

//profile
Route::get('/profile', 'ProfileController@index');
Route::post('/profile/add', 'ProfileController@store');
Route::post('/profile/update', 'ProfileController@update');
Route::post('/profile/change_status','ProfileController@change_status');
});