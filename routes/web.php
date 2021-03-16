<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoListController;
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

Route::get('/sign-in', [AuthController::class, 'init_signin'])->name('auth.sign-in');
Route::get('/sign-up', [AuthController::class, 'init_signup'])->name('auth.sign-up');
Route::get('/logout', [AuthController::class, 'request_logout'])->name('requests.logout');

Route::post('/api/sign-in', [AuthController::class, 'request_signin'])->name('requests.sign-in');
Route::post('/api/sign-up', [AuthController::class, 'request_signup'])->name('requests.sign-up');

Route::get('/', [TodoListController::class, 'init_index'])->name('home.index');
Route::get('/add', [TodoListController::class, 'init_add'])->name('home.add');
Route::get('/modify', [TodoListController::class, 'init_modify'])->name('home.modify');
Route::get('/remove', [TodoListController::class, 'init_remove'])->name('home.remove');

Route::post('/api/add-book', [TodoListController::class, 'request_add'])->name('requests.add');
Route::post('/api/modify-book', [TodoListController::class, 'request_modify'])->name('requests.modify');
Route::post('/api/remove-book', [TodoListController::class, 'request_remove'])->name('requests.remove');
