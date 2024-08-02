<?php

use App\Http\Controllers\HomeController;
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
Route::get('/invitations', [HomeController::class, 'showInvitation'])->middleware('auth')->name('invitations.index');
Route::get('/results', [HomeController::class, 'showResults'])->name('profile.results');
Route::get('/my_profile', [HomeController::class, 'showProfile'])->name('profile.my_profile');
Route::get('/uploadedFiles', [HomeController::class, 'uploadedFiles'])->name('profile.uploadedFiles');


Route::resource('projects', ProjectController::class)->middleware('auth');
Route::post('projects/{project}/invite', [ProjectController::class, 'invite'])->name('projects.invite')->middleware('auth');
Route::post('projects/{project}/invite', [ProjectController::class, 'invite'])->name('projects.invite')->middleware('auth');
Route::post('projects/{project}/accept', [ProjectController::class, 'acceptInvitation'])->name('projects.acceptInvitation')->middleware('auth');


Route::post('projects/{project}/messages', [MessageController::class, 'store'])->name('messages.store')->middleware('auth');


Route::post('/store_to_project', [FileController::class, 'store_to_project'])->name('store_to_project')->middleware('auth');
Route::post('/store_to_user', [FileController::class, 'store_to_user'])->name('store_to_user')->middleware('auth');
Route::post('/files/{file}/make-active', [FileController::class, 'makeActive'])->name('files.makeActive');


Route::post('files/{file}/associate', [FileController::class, 'associateUserJson'])->name('files.associateJson')->middleware('auth');
Route::post('/analyze/analyze_data/associate-json', [FileController::class, 'associateUserJson_from_analyze'])->name('analyze.analyze_data.associateJson');
Route::get('/analyze/analyze_data', [FileController::class, 'analyzeData_from_user'])->name('analyze.analyze_data');
