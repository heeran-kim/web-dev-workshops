<?php
/* Functions for PM database example. */

/* Load sample data, an array of associative arrays. */
include "pms.php";

/* Search sample data for $name or $year or $state from form. */
function search($query) {
    global $pms; 

	if (!empty($query)) {
		$results = array();
		foreach ($pms as $pm) {
			// If the query matches any of the 'name', 'from', 'to', or 'state' fields (case-insensitive)
			if (stripos ($pm['name'], $query) !== false ||
				stripos ($pm['from'], $query) !== false ||
				stripos ($pm['to'], $query) !== false ||
				stripos ($pm['state'], $query) !== false)
				{
				$results[] = $pm;
			}
		}
		$pms = $results;
	}

	return $pms;
}
?>
