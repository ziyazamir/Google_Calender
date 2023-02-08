<?php

use App\Http\Controllers\GoogleCalenderController;
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
    return view('welcome');
});
// Route::resource('cal')
// Route::resource('cal', ['GoogleCalenderController']);
Route::get('/oauth', [GoogleCalenderController::class, 'oauth'])->name('oauthCallback');
Route::get('/index', [GoogleCalenderController::class, 'index'])->name('index');
Route::get('/logout', [GoogleCalenderController::class, 'logout'])->name('logout');
