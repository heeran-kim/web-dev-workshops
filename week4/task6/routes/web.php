<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostingController;

// index: show all postings & comments
Route::get('/', [PostingController::class, 'index']);

// create: show create posting form
Route::get('/postings/create', [PostingController::class, 'create'])->middleware('auth');

// store: store posting info into database
Route::post('/postings', [PostingController::class, 'store'])->middleware('auth');

// store: store comment info into database
Route::post('/postings/{posting}/comments/add', [PostingController::class, 'storeComments'])->middleware('auth');

// create: show create user form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// store: store user info into database
Route::post('/users', [UserController::class, 'store'])->middleware('guest');

// Delete posting
Route::delete('/postings/{posting}', [PostingController::class, 'destroy'])->middleware('auth');

// logout
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

// login: show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// authenticate
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->middleware('guest');
