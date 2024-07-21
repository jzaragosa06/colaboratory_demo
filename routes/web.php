<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MessageController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('projects', ProjectController::class)->middleware('auth');

Route::post('projects/{project}/invite', [ProjectController::class, 'invite'])->name('projects.invite')->middleware('auth');
// Route::get('projects/{project}/accept-invitation/{token}', [ProjectController::class, 'acceptInvitation'])->name('projects.acceptInvitation')->middleware('auth');
Route::post('projects/{project}/invite', [ProjectController::class, 'invite'])->name('projects.invite')->middleware('auth');
Route::post('projects/{project}/accept', [ProjectController::class, 'acceptInvitation'])->name('projects.acceptInvitation')->middleware('auth');


Route::get('/invitations', function () {
    return view('invitations.index');
})->middleware('auth')->name('invitations.index');

Route::post('files', [FileController::class, 'store'])->name('files.store')->middleware('auth');

// routes/web.php
Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

Route::post('projects/{project}/messages', [MessageController::class, 'store'])->name('messages.store')->middleware('auth');

Route::post('files/{file}/associate', [FileController::class, 'associateJson'])->name('files.associateJson')->middleware('auth');



Route::get('/user/files', [FileController::class, 'showUserFiles'])->name('user.files');
Route::post('/user/files/{file}/associate-json', [FileController::class, 'associateUserJson'])->name('user.files.associateJson');