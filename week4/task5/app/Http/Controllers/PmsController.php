<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Functions for PM database example. */
/* Search sample data for $name or $year or $state from form. */
function searchPms($name, $year, $state) {
    $pms = getPms();
  
    // Filter $pms by $name
    if (!empty($name)) {
      $results = array();
      foreach ($pms as $pm) {
        if (stripos($pm['name'], $name) !== FALSE) {
          $results[] = $pm;
        }
      }
      $pms = $results;
    }
  
    // Filter $pms by $year
    if (!empty($year)) {
      $results = array();
      foreach ($pms as $pm) {
        if (strpos($pm['from'], $year) !== FALSE || 
            strpos($pm['to'], $year) !== FALSE) {
          $results[] = $pm;
        }
      }
      $pms = $results;
    }
  
    // Filter $pms by $state
    if (!empty($state)) {
      $results = array();
      foreach ($pms as $pm) {
        if (stripos($pm['state'], $state) !== FALSE) {
          $results[] = $pm;
        }
      }
      $pms = $results;
    }
  
    return $pms;
  }

class PmsController extends Controller
{
    public function index() {
        return view('pms.index');
    }

    public function search() {
        $query = request()->validate([
          'name' => 'nullable', 'min:3',
          'year' => ['nullable', 'numeric', 'min:1800', 'max:'.date('Y')],
          'state' => ['nullable', 'in:New South Wales,Victoria,Queensland,Tasmania,Western Australia']
        ]);
        
        $pms = searchPms($query['name'], $query['year'], $query['state']);

        session(['query' => $query, 'pms' => $pms]);

        return redirect('show');
    }

    public function show() {
        return view('pms.show')->with('pms', session('pms'))->with('query', session('query'));
    }

    public function edit() {
      return view('pms.edit')->with('query', session('query'));
    }
}
