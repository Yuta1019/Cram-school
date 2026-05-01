<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', function () {
    return view('auth.login');
})->name('top');

Route::post('/signup/confirm', 'Auth\RegisterController@confirm')->name('signup.confirm');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// 問い合わせ
Route::get('/inquiry', 'InquiryController@index')->name('inquiry.index');
Route::get('/inquiry/create', 'InquiryController@create')->name('inquiry.create');
Route::post('/inquiry', 'InquiryController@store')->name('inquiry.store');