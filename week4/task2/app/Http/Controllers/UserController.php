<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Filename: UserController.php
 * Author: Heeran Kim
 * Created Date: 2024-08-15
 * Last Modified: 2024-08-16
 * Description: This controller handles various user-related actions, including 
 *              creating, storing, editing, and updating user information within 
 *              the session. The controller demonstrates the use of Laravel's session 
 *              and request handling mechanisms.
 * 
 * Session Management in Laravel:
 * - Sessions provide a way to store user information across multiple requests.
 * - In this controller, user information is stored in the session after form validation.
 * - `session()` method is used to store data in the session, making it accessible throughout 
 *   the user's session.
 * - `with()` method is used for flash data that is available only for the next request.
 * 
 * Request Handling:
 * - `Request` objects are passed to methods like `store()` and `update()` to access 
 *   user input data.
 * - Validation of user input is done using the `$request->validate()` method.
 * 
 * Redirecting vs. Returning Views:
 * - `redirect()` can be thought of as an "ActionScript" that sends the user to a different route, 
 *   often after processing data, like storing form data. It triggers a new HTTP request.
 * - `view()` can be thought of as a "ViewScript" that directly returns a specific view, often passing 
 *   along data for that view to display, without triggering a new HTTP request.
 */

class UserController extends Controller
{
    /**
     * Display the homepage.
     * 
     * This method returns the 'index' view when the user accesses the root URL.
     */
    public function index() {
        return view('users.index');
    }

    /**
     * Show the form for creating a new entry.
     * 
     * This method returns the 'create' view where the user can fill out a form.
     */
    public function create() {
        return view('users.create');
    }

    /**
     * Store the data submitted from the form in the session.
     * 
     * This method handles form submission, validates the input, and stores the data in the session.
     * The session is used here because we want the data to persist across multiple requests.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        // Validate the input data
        // You can also use request()->validate() instead of $request->validate().
        $formFields = $request->validate([
            'name' => ['required', 'min:3', 'max:50'],
            'age' => ['required', 'numeric', 'min:18', 'max:100']
        ]);

        // Store the validated data in the session
        session(['listing' => $formFields]);

        // Redirect to the 'greeting' page with a flash message
        // 'with' is used here because we only need the flash message to persist for the next request
        return redirect('greeting')->with('flashMessage', 'Your information submitted successully!');
    }

    /**
     * Show the greeting page.
     * 
     * This method returns the 'greeting' view. The data stored in the session is used here.
     */
    public function greeting() {
        return view('users.greeting');
    }

    /**
     * Show the form to edit the existing entry.
     * 
     * This method returns the 'edit' view where the user can modify their previous entry.
     * The session data is still available because it was stored earlier in the 'store' method.
     */
    public function edit() {
        return view('users.edit');
    }

    /**
     * Update the data in the session with the new form submission.
     * 
     * This method handles the submission of the edit form, validates the input,
     * updates the session data, and redirects the user back to the 'greeting' page with a new flash message.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {
        // Validate the updated form data
        $formFields = $request->validate([
            'name' => ['required', 'min:3', 'max:50'],
            'age' => ['required', 'numeric', 'min:18', 'max:100']
        ]);

        // Update the session data with the new validated data
        session(['listing' => $formFields]);

        // Redirect to the 'greeting' page with a flash message indicating successful update
        return redirect('greeting')->with('flashMessage', 'Your information updated successully!');
    }
}
