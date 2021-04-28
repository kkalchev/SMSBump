<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/', [AuthController::class, 'index'])->name('register');
Route::get('/verify', [AuthController::class, 'verify'])->name('verify');
Route::get('/home', [AuthController::class, 'home'])->name('home');
Route::post('/submit-Form', [AuthController::class, 'submitForm'])->name('submitForm');
Route::post('/verify', [AuthController::class, 'submitOtp'])->name('verify');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
