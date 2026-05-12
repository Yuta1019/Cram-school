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
Route::get('/inquiry/{inquiry}', 'InquiryController@show')->name('inquiry.show');
Route::get('/inquiry/{inquiry}/edit', 'InquiryController@edit')->name('inquiry.edit');
Route::post('/inquiry/{inquiry}/confirm', 'InquiryController@confirmEdit')->name('inquiry.confirmEdit');
Route::put('/inquiry/{inquiry}', 'InquiryController@update')->name('inquiry.update');
Route::get('/inquiry/{inquiry}/delete', 'InquiryController@confirmDelete')->name('inquiry.confirmDelete');
Route::delete('/inquiry/{inquiry}', 'InquiryController@destroy')->name('inquiry.destroy');

// 連絡履歴
Route::get('/inquiry/{inquiry}/contacts', 'ContactController@index')->name('contact.index');
Route::get('/inquiry/{inquiry}/contacts/create', 'ContactController@create')->name('contact.create');
Route::post('/inquiry/{inquiry}/contacts', 'ContactController@store')->name('contact.store');

// 所感
Route::get('/inquiry/{inquiry}/lesson-notes/create', 'LessonNoteController@create')->name('lesson_note.create');
Route::post('/inquiry/{inquiry}/lesson-notes', 'LessonNoteController@store')->name('lesson_note.store');

// 体験会予約
Route::get('/inquiry/{inquiry}/trial-reservation', 'TrialReservationController@create')->name('trial.reservation.create');
Route::post('/inquiry/{inquiry}/trial-reservation/confirm', 'TrialReservationController@confirm')->name('trial.reservation.confirm');
Route::post('/inquiry/{inquiry}/trial-reservation', 'TrialReservationController@store')->name('trial.reservation.store');
Route::delete('/trial-reservations/{reservation}', 'TrialReservationController@destroy')->name('trial.reservation.destroy');

// 体験会
Route::get('/trial-events', 'TrialEventController@index')->name('trial.index');
Route::get('/trial-events/create', 'TrialEventController@create')->name('trial.create');
Route::get('/trial-events/calendar', 'TrialEventController@calendar')->name('trial.calendar');
Route::post('/trial-events', 'TrialEventController@store')->name('trial.store');
Route::get('/trial-events/{trialEvent}', 'TrialEventController@show')->name('trial.show');
Route::get('/trial-events/{trialEvent}/edit', 'TrialEventController@edit')->name('trial.edit');
Route::post('/trial-events/{trialEvent}/confirm', 'TrialEventController@confirmEdit')->name('trial.confirmEdit');
Route::put('/trial-events/{trialEvent}', 'TrialEventController@update')->name('trial.update');
Route::get('/trial-events/{trialEvent}/delete', 'TrialEventController@confirmDelete')->name('trial.confirmDelete');
Route::delete('/trial-events/{trialEvent}', 'TrialEventController@destroy')->name('trial.destroy');