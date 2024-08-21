<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // create: show create user form
    function create() {
        return view('users.create');
    }

    // store: store user info into database
    function store(Request $request) {
        $formFields = $request->validate([
            'name' => ['required', 'min:3', 'max:50'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6']
        ]);

        if($request->hasFile('photo')) {
            $formFields['photo'] = $request->file('photo')->store('images/users', 'public');
        }
        
        // Hash Password; you never want to store plain password (bcrypt.js)
        $formFields['password'] = bcrypt($formFields['password']);

        $user = User::create($formFields);
        
        // Login
        auth()->login($user);
        
        return redirect('/');
    }

    // logout
    function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // login
    function login() {
        return view('users.login');
    }

    // authenticate
    function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();
            return redirect('/');
        }
        
        return back();
    }
}
