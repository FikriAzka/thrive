<?php

use Illuminate\Http\Request;
use App\Jobs\CreateGoogleMeetLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\GoogleMeetController;


Route::get('/', function () {
    return view('auth.login');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/calendar', function () {
    return view('calendar');
});



Route::resource('meetings', MeetingController::class);
Route::post('/get-users', [MeetingController::class, 'getUsers'])->name('get-users');
Route::patch('/meetings/{meeting}/complete', [MeetingController::class, 'complete'])->name('meetings.complete');
Route::patch('/meetings/{meeting}/upload', [MeetingController::class, 'upload'])->name('meetings.upload');

Route::get('google/oauth', [GoogleMeetController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('google/oauth/callback', [GoogleMeetController::class, 'handleGoogleCallback']);
Route::post('meetings/create-google-meet', [GoogleMeetController::class, 'createGoogleMeet'])->name('meetings.create-google-meet');
Route::get('meetings/{meeting}/meet-status', [MeetingController::class, 'checkMeetStatus'])->name('meetings.check-meet-status');



Route::get('/ratings/{meeting}/create', [RatingController::class, 'showDataForm'])->name('ratings.create');
Route::post('/ratings/{meeting}/store-data', [RatingController::class, 'storeData'])->name('ratings.store-data');
Route::get('/ratings/{meeting}/form', [RatingController::class, 'showRatingForm'])->name('ratings.form');
Route::post('/ratings/{meeting}/store', [RatingController::class, 'storeRating'])->name('ratings.store');
Route::get('/ratings/{meeting}/form2', [RatingController::class, 'showRatingForm2'])->name('ratings.form2');
Route::post('/ratings/{meeting}/store-final', [RatingController::class, 'storeFinalRating'])->name('ratings.store.final');
Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');

Route::get('google/success', function () {
    // Misalnya, proses menyimpan token berhasil dilakukan di sini
    
    // Mengarahkan pengguna kembali ke halaman sebelumnya
    return back()->with('success', 'Token berhasil disimpan!');
})->name('google.success');

Route::get('google/error', function () {
    return 'Terjadi kesalahan.';
})->name('google.error');

