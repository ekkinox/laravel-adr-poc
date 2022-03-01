<?php

use App\Http\Controllers\ErrorAction;
use App\Http\Controllers\SuccessAction;
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

// ADR actions
Route::get('/success', SuccessAction::class);
Route::get('/error', ErrorAction::class);
