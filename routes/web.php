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

Route::get('/sign-in', [AuthController::class, 'initSignin'])->name('auth.sign-in');
Route::get('/sign-up', [AuthController::class, 'initSignup'])->name('auth.sign-up');
Route::get('/logout', [AuthController::class, 'requestLogout'])->name('requests.logout');

Route::post('/api/sign-in', [AuthController::class, 'requestSignin'])->name('requests.sign-in');
Route::post('/api/sign-up', [AuthController::class, 'requestSignup'])->name('requests.sign-up');

Route::get('/', [TodoListController::class, 'initIndex'])->name('home.index');
Route::get('/add', [TodoListController::class, 'initAdd'])->name('home.add');
Route::get('/modify', [TodoListController::class, 'initModify'])->name('home.modify');
Route::get('/modify/{id}', [TodoListController::class, 'initModify'])->name('home.modify-id');
Route::get('/remove', [TodoListController::class, 'initRemove'])->name('home.remove');
Route::get('/remove/{id}', [TodoListController::class, 'initRemove'])->name('home.remove-id');

Route::post('/api/add-book', [TodoListController::class, 'requestAdd'])->name('requests.add');
Route::post('/api/modify-book', [TodoListController::class, 'requestModify'])->name('requests.modify');
Route::post('/api/remove-book', [TodoListController::class, 'requestRemove'])->name('requests.remove');
