<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Filename: GetValueController.php
 * Author: Heeran Kim
 * Created Date: 2024-08-16
 * Last Modified: 2024-08-16
 * Description: This controller handles the display of the homepage and the result page 
 *              based on the GET values entered in the URL.
 */

class GetValueController extends Controller
{
     /**
      * Display the homepage.
      * 
      * This method returns the 'index' view when the user accesses the root URL.
      */
     public function index() {
         return view('index');
     }
 
     /**
      * Show the result page.
      * 
      * This method returns the 'show' view, displaying results based on the 
      * GET values passed in the URL.
      */
     public function show() {
        return view('show')->with('getValues', request()->all());
     }
}
