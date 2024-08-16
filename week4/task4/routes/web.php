<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Define the route for the homepage (index)
Route::get('/', [UserController::class, 'index']);

// Define the route to display the form for creating a new entry
Route::get('/create', [UserController::class, 'create']);

// Define the route to handle form submission and store data in session
Route::post('/store', [UserController::class, 'store']);

// Define the route to display a greeting page after data submission
Route::get('/greeting', [UserController::class, 'greeting']);

// Define the route to display the form for editing an existing entry
Route::get('/edit', [UserController::class, 'edit']);

// Define the route to handle the form submission for updating an entry
Route::put('/update', [UserController::class, 'update']);
