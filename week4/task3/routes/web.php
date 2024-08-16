<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetValueController;

/**
 * Filename: web.php
 * Author: Heeran Kim
 * Created Date: 2024-08-16
 * Last Modified: 2024-08-16
 * Description: This file contains the route declarations for the application.
 */

// Define the route for the homepage (index)
Route::get('/', [GetValueController::class, 'index']);

// Define the route that displays a result page based on the GET value entered in the URL
Route::get('/result', [GetValueController::class, 'show']);
