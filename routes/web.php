<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\GoogleMeetController;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/calendar', function () {
    return view('calendar');
});



Route::resource('meetings', MeetingController::class);
Route::post('/get-users', [MeetingController::class, 'getUsers'])->name('get-users');
Route::get('/rating', [MeetingController::class, 'ratingPage'])->name('rating-page');
Route::patch('/meetings/{meeting}/complete', [MeetingController::class, 'complete'])->name('meetings.complete');
Route::put('/meetings/{id}/savenotes', [MeetingController::class, 'saveNotes'])->name('meetings.savenotes');

Route::get('meetings/{meeting}/download', [MeetingController::class, 'download'])->name('meetings.download');

Route::get('google/oauth', [GoogleMeetController::class, 'redirectToGoogle']);
Route::get('google/oauth/callback', [GoogleMeetController::class, 'handleGoogleCallback']);
Route::post('meetings/create-google-meet', [GoogleMeetController::class, 'createGoogleMeet'])->name('meetings.create-google-meet');

Route::get('google/success', function () {
    return 'Token berhasil disimpan!';
})->name('google.success');

Route::get('google/error', function () {
    return 'Terjadi kesalahan.';
})->name('google.error');





