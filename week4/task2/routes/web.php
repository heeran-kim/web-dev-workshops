<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Filename: web.php
 * Author: Heeran Kim
 * Created Date: 2024-08-15
 * Last Modified: 2024-08-16
 * Description: This file contains the route declarations for the application.
 * 
 * Laravel Routing Overview:
 * - This file contains the route declarations for the application.
 * - Routes define the paths (URLs) that users can access and specify the corresponding 
 *   controller methods that should handle the requests.
 * - In Laravel, routes can be defined using HTTP methods such as GET, POST, PUT, DELETE, etc.
 * - The Route class provides static methods for defining these routes.
 * 
 * Common Route Methods:
 * - Route::get()    : Defines a route that responds to a GET request.
 * - Route::post()   : Defines a route that responds to a POST request.
 * - Route::put()    : Defines a route that responds to a PUT request.
 * - Route::delete() : Defines a route that responds to a DELETE request.
 * - Route::match()  : Allows you to specify multiple HTTP methods that a route should respond to.
 *                      e.g. array('GET', 'POST')
 * - Route::any()    : Defines a route that can respond to any HTTP method.
 * 
 * The Route declaration has the following basic form:
 *     Route::http_method(path, function)
 *     1. The URL path (e.g., '/create').
 *     2. The controller method that handles the request, specified as [ControllerClass::class, 'methodName'].
 * - Instead of specifying a controller method, you can directly declare an anonymous function 
 *   (called a closure) within the route. Closures are useful for defining quick, inline behavior 
 *   for routes without needing a separate controller.
 * 
 * Viewing Routes in Laravel:
 * - You can list all the routes in your Laravel project by running the following Artisan command in your terminal:
 *   php artisan route:list
 * - This command provides a table showing all the routes, their HTTP methods, paths, and the corresponding
 *   controller methods or closures.
 */

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
