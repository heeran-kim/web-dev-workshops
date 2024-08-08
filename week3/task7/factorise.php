<?php
/**
 * This script performs factorisation of a given number and manages results in a text file.
 * 
 * Features:
 * - Retrieves and validates the number from a GET request. (Existing)
 * - Factorises the number using an included function. (Existing)
 * - Appends the factorisation result to a text file. (New)
 * - Displays all previous factorisation results. (New)
 * - Ensures the directory and file exist and have proper permissions. (New)
 */

include "includes/defs.php";

$number = $_GET['number'];
$error = "";
$dir = "result";
$filename = $dir . "/results.txt";
$results = array();

if (empty($number)) {
    $error = "Error: Missing value";
} else if (!is_numeric($number)) {
    $error = "Error: Nonnumeric value: $number";
} else if ($number < 2 || $number != strval(intval($number))) {
    $error = "Error: Invalid number: $number";
} else {
    $factors = factors($number);
    $factors = join(" . ", $factors);

    // Check if the directory exists
    if (!is_dir($dir)) {
      die("Directory does not exist.");
    }
    
    // Check if file exists and is writable
    if (!file_exists($filename)) {
      die("File does not exist.");
    } elseif (!is_writable($filename)) {
      // If the file is read-only or the file permissions are incorrectly set
      die("File is not writable.");
    }
    
    // Try to open the file for appending
    // access mode
    //   "r": to read the file.
    //        return false if the file is not existed.
    //   "a": to create a new file, or append to an existing file
    //        The file pointer is at the end of the file.
    //   "w": to create a new file, or overwrite an existing file
    //        The file pointer is at the beginning of the file. (erases the content)
    $fp = fopen($filename, "a");
    if (!$fp) {
      // If there is an issue opening the file, such as problems with the file system
      echo "Unable to open the file for appending.";
    } else {
      // Write the factorisation result to the file
      fputs($fp, $number . "=" . $factors . "\n");
      fclose($fp);
    }
}

# Check if the directory exists
if (!is_dir($dir)) {
  die("Directory does not exist.");
}

# Check if file exists and is readable
if (!file_exists($filename)) {
  die("File does not exist.");
} elseif (!is_readable($filename)) {
  die("File is not readable.");
}

# Try to open the file for reading
$fp = fopen($filename, "r");
if (!$fp) {
  echo "Unable to open the file for reading.";
} else {
  while (!feof($fp)){
     $results[] = fgets($fp, 4096);
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Factors</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/wp.css">
  </head>
  
  <body>  
    <h1>Factorisation</h1>
    <?php
        // Display the results or the error message
        if (!$error){
          // No error: Display the most recent result (last entry)
          echo "{$results[count($results)-2]} <br><br>";
          // Display all previous results except the most recent one
          for ($i = 0; $i < count($results)-2; $i++){
            echo "{$results[$i]} <br>";
          }
        } else {
          // There was an error: Display the error message
          echo "$error <br><br>";
          // Display all previous results including the most recent valid one
          for ($i = 0; $i < count($results)-1; $i++){
            echo "{$results[$i]} <br>";
          }
        }
    ?>

    <h3>Another?</h3>

    <form method="get" action="factorise.php">
      <p>Number to factories: <input type="text" name="number" value="<?= $number ?>"
      <p><input type="submit" value="Factorise it!">
    </form>
    <hr>
    <p>
    Sources:
    <a href="show.php?file=factorise.php">factorise.php</a>
    <a href="show.php?file=includes/defs.php">includes/defs.php</a>
    </p>
  </body>
</html>
