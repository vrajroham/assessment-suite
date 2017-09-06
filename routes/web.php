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
    return view('home.home');
})->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/superuser/setup', 'SuperuserRegistration@registrationStep1')->name('superuser.register');
Route::post('/superuser/setup', 'SuperuserRegistration@saveRegistrationStep1')->name('superuser.register.save');
Route::get('/demo', 'WelcomeController@index')->name('demo');
