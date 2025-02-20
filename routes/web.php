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
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProjectManagementController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/projectmanagement', [ProjectManagementController::class, 'index'])->name('projectmanagement.index');
Route::get('/projectmanagement/create', [ProjectManagementController::class, 'create'])->name('projectmanagement.create');
Route::post('/projectmanagement', [ProjectManagementController::class, 'store'])->name('projectmanagement.store');
Route::get('/projectmanagement/{id}', [ProjectManagementController::class, 'show'])->name('projectmanagement.show');
Route::get('/projectmanagement/{id}/edit', [ProjectManagementController::class, 'edit'])->name('projectmanagement.edit');
Route::put('/projectmanagement/{id}', [ProjectManagementController::class, 'update'])->name('projectmanagement.update');
Route::delete('/projectmanagement/{id}', [ProjectManagementController::class, 'destroy'])->name('projectmanagement.destroy');


// Route untuk ratings (dikecualikan dari middleware auth)
Route::get('/ratings/{meeting}/create', [RatingController::class, 'showDataForm'])->name('ratings.create');
// Route::get('/ratings/{meeting}/{token}', [RatingController::class, 'showDataForm'])->name('ratings.create');

Route::post('/ratings/toggle-access/{meeting}', [RatingController::class, 'toggleAccess'])->name('ratings.toggle-access');

Route::post('/ratings/{meeting}/store-data', [RatingController::class, 'storeData'])->name('ratings.store-data');
Route::get('/ratings/{meeting}/form', [RatingController::class, 'showRatingForm'])->name('ratings.form');
Route::post('/ratings/{meeting}/store', [RatingController::class, 'storeRating'])->name('ratings.store');
Route::get('/ratings/{meeting}/form2', [RatingController::class, 'showRatingForm2'])->name('ratings.form2');
Route::post('/ratings/{meeting}/store-final', [RatingController::class, 'storeFinalRating'])->name('ratings.store.final');
Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
Route::get('/ratings/meeting/{meeting}', 'RatingController@show')->name('ratings.meeting.show');

// Grup Middleware Auth
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::get('/calendar', [CalendarController::class, 'showCalendar'])->name('calendar');

    Route::resource('meetings', MeetingController::class);
    Route::post('/get-users', [MeetingController::class, 'getUsers'])->name('get-users');
    Route::patch('/meetings/{meeting}/complete', [MeetingController::class, 'complete'])->name('meetings.complete');
    Route::patch('/meetings/{meeting}/upload', [MeetingController::class, 'upload'])->name('meetings.upload');

    Route::get('google/oauth', [GoogleMeetController::class, 'redirectToGoogle'])->name('google.auth');
    Route::get('google/oauth/callback', [GoogleMeetController::class, 'handleGoogleCallback']);
    Route::post('meetings/create-google-meet', [GoogleMeetController::class, 'createGoogleMeet'])->name('meetings.create-google-meet');
    Route::get('meetings/{meeting}/meet-status', [MeetingController::class, 'checkMeetStatus'])->name('meetings.check-meet-status');

    Route::get('google/success', function () {
        return back()->with('success', 'Token berhasil disimpan!');
    })->name('google.success');

    Route::get('google/error', function () {
        return 'Terjadi kesalahan.';
    })->name('google.error');
});
