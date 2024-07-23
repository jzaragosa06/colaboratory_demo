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

// Route::post('files', [FileController::class, 'store'])->name('files.store')->middleware('auth');
Route::post('/store_from_project', [FileController::class, 'store_from_project'])->name('store_from_project')->middleware('auth');
Route::post('/store_not_from_project', [FileController::class, 'store_not_from_project'])->name('store_not_from_project')->middleware('auth');



Route::get('/uploadedFiles', [FileController::class, 'uploadedFiles'])->name('uploadedFiles');


Route::post('projects/{project}/messages', [MessageController::class, 'store'])->name('messages.store')->middleware('auth');

Route::post('files/{file}/associate', [FileController::class, 'associateUserJson'])->name('files.associateJson')->middleware('auth');



Route::get('/results', [FileController::class, 'showResults'])->name('results');
Route::get('/analyze/analyze_data', [FileController::class, 'showUserFiles'])->name('analyze.analyze_data');
Route::post('/analyze/analyze_data/associate-json', [FileController::class, 'associateUserJson'])->name('analyze.analyze_data.associateJson');
Route::get('my_profile', function () {
    return view('my_profile');
})->name('my_profile');

Route::post('/files/{file}/make-active', [FileController::class, 'makeActive'])->name('files.makeActive');
