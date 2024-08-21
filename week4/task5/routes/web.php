<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PmsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require(app_path().'/pms.php');  // The file containing the pms array is in the app directory. 
                                 // app_path() give us the path the the app directory

// Display search form
Route::get('/', [PmsController::class, 'index']);

// Perform search
Route::post('search', [PmsController::class, 'search']);

// Display results
Route::get('show', [PmsController::class, 'show']);

// Display edit form
Route::get('edit', [PmsController::class, 'edit']);

